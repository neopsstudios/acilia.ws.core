<?php
namespace WS\Core\Command\DBLogger;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ArchiveCommand extends Command
{
    const ARCHIVE_DAYS = 30;

    private $em = null;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('ws:dblogger:archive')
            ->setDescription('Moves the log to it\'s archive')
            ->addOption(
                'days',
                'd',
                InputOption::VALUE_REQUIRED,
                sprintf('Number of days to preserve, default %s days', self::ARCHIVE_DAYS),
                self::ARCHIVE_DAYS
            )
            ->addOption(
                'purge',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Number of days to preserve on the archive, purge older, default none'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $days = $input->getOption('days');
            $purge = $input->getOption('purge');

            if (!is_numeric($days)) {
                throw new \Exception('Days is not a valid number');
            }

            if ($days > $purge) {
                throw new \Exception(sprintf('Purge days (%s) must be greater than Archive days (%s)', $purge, $days));
            }

            // Calculate now
            $now = new \DateTime();
            $now->setTime(0, 0, 0);
            $now->modify('-' . $days . ' days');

            // Get connection
            $connection = $this->em->getConnection();

            // Archive logs
            $output->write('Archiving logs... ');
            $stmt = $connection->prepare('INSERT INTO ws_log_archive SELECT * FROM log WHERE log_datetime < ?');
            $stmt->bindValue(1, $now->format('Y-m-d'));
            $stmt->execute();
            $output->writeln('OK');

            // Delete logs
            $output->write('Deleting logs... ');
            $stmt = $connection->prepare('DELETE FROM ws_log WHERE log_datetime < ?');
            $stmt->bindValue(1, $now->format('Y-m-d'));
            $stmt->execute();
            $output->writeln('OK');

            if (is_numeric($purge)) {
                $now = new \DateTime();
                $now->setTime(0, 0, 0);
                $now->modify('-' . $purge . ' days');

                // Delete Archived logs
                $output->write('Purge Archived logs... ');
                $stmt = $connection->prepare('DELETE FROM ws_log_archive WHERE log_datetime < ?');
                $stmt->bindValue(1, $now->format('Y-m-d'));
                $stmt->execute();
                $output->writeln('OK');
            }

            return 0;
        } catch (\Exception $e) {
            $output->writeln(sprintf('ERROR: %s', $e->getMessage()));
            return 1;
        }
    }
}

<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Filesystem\Filesystem;
use DateTimeImmutable;

#[AsCommand(
    name: 'app:generate-migration-template',
    description: 'generatin a template migration with this name class name VersionYYYYMMDDHHMMSS',
)]
class AppCommandGenerateMigrationTemplateCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();
        $migrationDir = 'migrations';

        // Génération du nom au format VersionYYYYMMDDHHMMSS
        $timestamp = (new DateTimeImmutable())->format('YmdHis');
        $fileName = "Version{$timestamp}.php";
        $filePath = $migrationDir . '/' . $fileName;

        if (!$filesystem->exists($migrationDir)) {
            $filesystem->mkdir($migrationDir);
        }

        // Contenu du fichier template
        $templateContent = <<<PHP
<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version{$timestamp} extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoutez ici une description de la migration';
    }

    public function up(Schema \$schema): void
    {
        // Ajoutez ici le code pour appliquer les changements
    }

    public function down(Schema \$schema): void
    {
        // Ajoutez ici le code pour revenir en arrière
    }
}
PHP;

        if (!$filesystem->exists($filePath)) {
            $filesystem->dumpFile($filePath, $templateContent);
            $output->writeln('<info>Fichier de migration généré : ' . $filePath . '</info>');
        } else {
            $output->writeln('<comment>Le fichier existe déjà : ' . $filePath . '</comment>');
        }

        return Command::SUCCESS;
    }
}

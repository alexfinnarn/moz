<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:generate-static-pages',
    description: 'Generate static pages from sitemap.xml',
)]
class GenerateStaticPagesCommand extends Command
{
    private HttpKernelInterface $httpKernel;
    private KernelInterface $kernel;

    public function __construct(HttpKernelInterface $httpKernel, KernelInterface $kernel)
    {
        $this->httpKernel = $httpKernel;
        $this->kernel = $kernel;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sitemapPath = __DIR__ . '/../../public/sitemap.xml';
        $projectDir = $this->kernel->getProjectDir();

        // Clear the static directory.
        $staticDir = $projectDir . '/public/static';
        $files = glob($staticDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
                $output->writeln("Deleted static file: $file");
            }
        }

        // Load and parse the sitemap.xml
        $doc = new \DOMDocument();
        $doc->load($sitemapPath);

        $urls = $doc->getElementsByTagName('loc');

        foreach ($urls as $url) {
            $urlString = $url->nodeValue;

            // Use Symfony's HttpKernel to render the page
            $request = Request::create($urlString);
            $response = $this->httpKernel->handle($request, HttpKernelInterface::SUB_REQUEST);

            $html = $response->getContent();

            // Get hostname.
            $components = parse_url($urlString);
            $host = $components['host'];

            // If  then basename is index otherwise basename.
            $basename =  $components['host'] === $components['path'] ? 'index' : basename($urlString);

            // Generate the file path and save the HTML
            $filePath = $projectDir . '/public/static/'. $basename . '.html';
            file_put_contents($filePath, $html);

            $output->writeln("Generated static file for: $urlString");
        }

        // Copy CSS in $projectDir/public/css to $projectDir/public/static/css.
        $cssDir = $projectDir . '/public/css';
        $cssFiles = glob($cssDir . '/*');
        foreach ($cssFiles as $cssFile) {
            $cssFileName = basename($cssFile);
            $cssFileStaticPath = $projectDir . '/public/static/css/' . $cssFileName;
            copy($cssFile, $cssFileStaticPath);
            $output->writeln("Copied CSS file: $cssFileStaticPath");
        }

        return Command::SUCCESS;
    }
}

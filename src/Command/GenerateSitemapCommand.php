<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;

#[AsCommand(
    name: 'app:generate-sitemap',
    description: 'Generate sitemap.xml with all site links',
)]
class GenerateSitemapCommand extends Command
{

    private RouterInterface $router;
    private KernelInterface $kernel;

    public function __construct(RouterInterface $router, KernelInterface $kernel, ParameterBagInterface $params)
    {
        $this->router = $router;
        $this->kernel = $kernel;
        $this->params = $params;
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
        $routeCollection = $this->router->getRouteCollection();
        $base_url = $this->params->get('app.base_url');
        $xml = new \SimpleXMLElement('<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        foreach ($routeCollection as $routeName => $route) {
            if (str_starts_with($routeName, '_')) {
                continue;
            }

            $path = $route->getPath();
            $excludedPaths = [
                '/posts/show/{slug}/teaser',
            ];
            if (in_array($path, $excludedPaths)) {
                continue;
            }

            if ($path === '/posts/show/{slug}') {
                $projectDir = $this->kernel->getProjectDir();
                $directory = $projectDir . $this->params->get('posts.post_directory');
                $files = glob($directory . '/*.md');

                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'md') {
                        // Get slug from path. File name is in pattern YYYY-MM-DD_slug.md.
                        $slug = pathinfo($file, PATHINFO_FILENAME);
                        $slug = explode('_', $slug)[1];

                        $url = $xml->addChild('url');
                        $url->addChild('loc', $base_url . '/posts/' . $slug . '/show');
                        $url->addChild('changefreq', 'daily');
                    }
                }
                continue;
            }

            $url = $xml->addChild('url');
            $url->addChild('loc', $base_url . $path);
            $url->addChild('changefreq', 'daily');
        }

        $xmlContent = $xml->asXML();
        file_put_contents('public/sitemap.xml', $xmlContent);
        $output->writeln('Generated sitemap.xml');

        return Command::SUCCESS;
    }
}

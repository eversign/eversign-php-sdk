<?php

use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Symfony\Component\Finder\Finder;
use Sami\Parser\Filter\TrueFilter;


$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Test')
    ->in(__DIR__.'/../sdk/Eversign')
;

$sami = new Sami($iterator, array(
    'title'                => 'Eversign PHP SDK Documentation',
    'build_dir'            => __DIR__.'/build',
    'default_opened_level' => 2,
));

$sami['filter'] = function () {
    return new TrueFilter();
};

return $sami;

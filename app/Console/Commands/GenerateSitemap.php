<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command {
    /**
    * The console command name.
    *
    * @var string
    */
    protected $signature = 'sitemap:generate';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Generate the sitemap.';

    /**
    * Execute the console command.
    *
    * @return mixed
    */

    public function handle() {
        // modify this to your own needs
        SitemapGenerator::create( config( 'app.url' ) )
        ->getSitemap()
        ->add( Url::create( '/' )->setPriority( 1.0 ) )
        ->add( Url::create( route( 'contact' ) )->setPriority( 1.0 ) )
        ->add( Url::create( route( 'about' ) )->setPriority( 1.0 ) )
        ->add( Url::create( route( 'faq' ) )->setPriority( 1.0 ) )
        ->add( Url::create( route( 'downloads' ) )->setPriority( 0.9 ) )
        ->add( Url::create( route( 'pricing' ) )->setPriority( 1.0 ) )
        ->add( Url::create( route( 'getting-started' ) )->setPriority( 0.9 ) )
        // ->add( Url::create( route( 'demo-request' ) )->setPriority( 0.9 ) )
        ->add( Url::create( route( 'features' ) )->setPriority( 1.0 ) )
        ->add( Url::create( route( 'resources' ) )->setPriority( 0.9 ) )
        ->add( Url::create( route( 'church-website-builder' ) )->setPriority( 0.8 ) )
        ->add( Url::create( route( 'church-mobile-app' ) )->setPriority( 0.8 ) )
        ->add( Url::create( route( 'membership-management' ) )->setPriority( 0.8 ) )

        // ->add( Url::create( route( 'free-trial' ) )->setPriority( 0.9 ) )
        // ->add( Url::create( route( 'free-license' ) )->setPriority( 0.9 ) )
        // ->add( Url::create( route( 'hosted-edition' ) )->setPriority( 0.9 ) )
        ->writeToFile( public_path( 'sitemap.xml' ) );
    }
}

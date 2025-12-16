<?php
namespace Michel\PaperORMBundle;

use Michel\PaperORM\Proxy\ProxyFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;


final class PaperORMBundle extends Bundle
{
    public function boot()
    {
        if ($this->container->getParameter('paper_orm.proxy_autoload')) {
            ProxyFactory::registerAutoloader();
        }
    }

    public function getPath() : string
    {
        return \dirname(__DIR__);
    }
}

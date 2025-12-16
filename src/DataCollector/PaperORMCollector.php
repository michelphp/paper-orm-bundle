<?php

namespace Michel\PaperORMBundle\DataCollector;

use Michel\PaperORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class PaperORMCollector extends AbstractDataCollector
{

    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function collect(Request $request, Response $response, ?\Throwable $exception = null) : void
    {
        $debugger = $this->em->getConnection()->getPdo()->getSqlDebugger();
        $this->data = [
            'queries' => [],
            'count' => 0,
            'time' => 0,
        ];
        if ($debugger) {
            $this->data = [
                'queries' => $debugger->getQueries(),
                'count' => $debugger->getQueryCount(),
                'time' => $debugger->getTotalTime(),
            ];
        }
    }

    public function getName() : string
    {
        return 'paper_orm';
    }

    public function getQueryCount(): int
    {
        return $this->data['count'] ?? 0;
    }

    public function getTotalTime(): float
    {
        return $this->data['time'] ?? 0.0;
    }

    public function getQueries(): array
    {
        return $this->data['queries'] ?? [];
    }

    public function reset(): void
    {
        $this->data = [];
    }
}

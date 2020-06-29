<?php

declare(strict_types=1);

namespace JapSeyz\SimpleJDF;

class Job extends BaseJDF
{
    protected array $root_nodes = ['ResourcePool', 'ResourceLinkPool'];

    protected string $name;

    public function __construct()
    {
        parent::__construct();

        $this->initialiseMessage();
        $this->setSensibleDefaults();
    }

    public function setName(string $name): self
    {
        $this->root->addAttribute('DescriptiveName', $name);

        return $this;
    }

    public function setPrintFile(string $url, int $quantity = 1)
    {
        // add a layout element and filespec for this document within the ResourcePool
        $runlist = $this->resourcePool()->addChild('RunList');
        $runlist->addAttribute('Class', 'Parameter');
        $runlist->addAttribute('ID', 'RL1');
        $runlist->addAttribute('Status', 'Available');

        $layout_element = $runlist->addChild('LayoutElement');
        $file_spec = $layout_element->addChild('FileSpec');
        $file_spec->addAttribute('URL', $url);

        // now we need to reference our RunList in ResourceLinkPool
        $this->linkResource('RunList', 'Input', ['CombinedProcessIndex' => '0']);
        $this->linkResource('Component', 'Output', ['Amount' => $quantity, 'CombinedProcessIndex' => 1]);

        return $this;
    }

    protected function setSensibleDefaults()
    {
        $component = $this->resourcePool()->addChild('Component');
        $component->addAttribute('Class', 'Quantity');
        $component->addAttribute('ComponentType', 'FinalProduct');
        $component->addAttribute('ID', 'C1');
        $component->addAttribute('Status', 'Available');

        $digitalPrintingParams = $this->resourcePool()->addChild('DigitalPrintingParams');
        $digitalPrintingParams->addAttribute('Class', 'Parameter');
        $digitalPrintingParams->addAttribute('ID', 'DP1');
        $digitalPrintingParams->addAttribute('Status', 'Available');

        $layoutPreparationParams = $this->resourcePool()->addChild('LayoutPreparationParams');
        $layoutPreparationParams->addAttribute('Class', 'Parameter');
        $layoutPreparationParams->addAttribute('ID', 'LPP1');
        $layoutPreparationParams->addAttribute('Status', 'Available');

        $this->linkResource('LayoutPreparationParams', 'Input', ['CombinedProcessIndex' => '0']);
        $this->linkResource('DigitalPrintingParams', 'Input', ['CombinedProcessIndex' => '1']);
    }

    protected function linkResource(string $resource_name, string $usage, array $attributes = []): void
    {
        // validate the usage string
        if (! \in_array($usage, ['Input', 'Output'])) {
            throw new \InvalidArgumentException('$usage can only be Input or Output');
        }

        // validate the resource name
        if (null === $this->resourcePool()->{$resource_name}) {
            throw new \InvalidArgumentException('No ' . $resource_name . ' resource exists. Refusing to make link');
        }

        // create a link element for this resource
        $resource_link = $this->resourceLinkPool()->addChild($resource_name . 'Link');
        $resource_link->addAttribute('rRef', (string) $this->resourcePool()->{$resource_name}[0]->attributes()->ID);
        $resource_link->addAttribute('Usage', $usage);

        foreach ($attributes as $name => $value) {
            $resource_link->addAttribute((string) $name, (string) $value);
        }
    }
}

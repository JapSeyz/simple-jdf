<?php

declare(strict_types=1);

namespace JapSeyz\SimpleJDF;

use SimpleXMLElement;
use BadMethodCallException;
use Illuminate\Support\Str;

class BaseJDF
{
    protected SimpleXMLElement $root;
    protected String $author;
    protected array $root_nodes = ['AuditPool', 'ResourcePool', 'ResourceLinkPool'];

    public function __construct()
    {
        $this->author = config('simple-jdf.author', '');
    }

    public function __call($method, $arguments): SimpleXMLElement
    {
        $node_type = Str::Studly($method);

        if (! \in_array($node_type, $this->root_nodes, true)) {
            throw new BadMethodCallException('Unknown node type \'' . $node_type . '\'');
        }

        // return the node if it exists, or create a new one (so only ever one allowed)
        return $this->root->{$node_type} ?? $this->root->addChild($node_type);
    }

    public function asXML(): string
    {
        return $this->root->asXML();
    }

    /**
     * Build the standard JMF or JDF message object to get us started.
     */
    protected function initialiseMessage(): void
    {
        // These are used to generate the initial XML field attributes
        $xml_encoding = '<?xml version="1.0" encoding="UTF-8"?>';

        // Initialize the JMF or JDF root node
        $root = new SimpleXMLElement($xml_encoding . '<JDF xmlns="http://www.CIP4.org/JDFSchema_1_1" xmlns:EFI="http://www.efi.com/efijdf" xmlns:jdftyp="http://www.CIP4.org/JDFSchema_1_1_Types" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" />', \LIBXML_NOEMPTYTAG);
        $root->addAttribute('Activation', 'Active');
        $root->addAttribute('ID', 'ID1');
        $root->addAttribute('JobID', 'J_000000');
        $root->addAttribute('JobPartID', 'n_000015');
        $root->addAttribute('NamedFeatures', 'FieryVirtualPrinter GL');
        $root->addAttribute('Status', 'Ready');
        $root->addAttribute('Type', 'Combined');
        $root->addAttribute('Types', 'LayoutPreparation DigitalPrinting');
        $root->addAttribute('Version', '1.3');
        $this->root = $root;

        $this->setAuditMessage();
    }

    protected function setAuditMessage()
    {
        $created = $this->auditPool()->addChild('Created');
        $created->addAttribute('AgentName', $this->author);
        $created->addAttribute('AgentVersion', '1');
        $created->addAttribute('TimeStamp', now()->toIso8601String());
    }
}

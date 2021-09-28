<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace percipiolondon\craftstaff\gql\types\elements;

use craft\gql\types\elements\Element;
use craft\helpers\Json;

use GraphQL\Type\Definition\ResolveInfo;

use percipiolondon\craftstaff\elements\Employer as EmployerElement;
use percipiolondon\craftstaff\gql\interfaces\elements\Employer as EmployerInterface;


/**
 * Class Employer
 *
 * @author Percipio Global Ltd. <support@percipio.london>
 * @since 1.0.0
 */
class Employer extends Element
{
    /**
     * @inheritdoc
     */
    public function __construct(array $config)
    {
        $config['interfaces'] = [
            EmployerInterface::getType(),
        ];

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    protected function resolve($source, $arguments, $context, ResolveInfo $resolveInfo)
    {
        /** @var EmployerElement $source */
        $fieldName = $resolveInfo->fieldName;

        switch($fieldName) {
            case 'address':
               return Json::decodeIfJson($source->address);

            case 'hmrcDetails':
                return Json::decodeIfJson($source->hmrcDetails);

            case 'defaultPayOptions':
                return Json::decodeIfJson($source->defaultPayOptions);
        }

        return parent::resolve($source, $arguments, $context, $resolveInfo);
    }
}
<?php
/**
 * Copyright 2016 Xenofon Spafaridis
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Phramework\DataTemplate\Validator;

use Phramework\Validate\ArrayValidator;
use Phramework\Validate\BaseValidator;
use Phramework\Validate\BooleanValidator;
use Phramework\Validate\DatetimeValidator;
use Phramework\Validate\DateValidator;
use Phramework\Validate\EnumValidator;
use Phramework\Validate\IntegerValidator;
use Phramework\Validate\NumberValidator;
use Phramework\Validate\ObjectValidator;
use Phramework\Validate\OneOf;
use Phramework\Validate\Result\Result;
use Phramework\Validate\StringValidator;
use Phramework\Validate\UnsignedIntegerValidator;

/**
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 */
class DataTemplate
{
    /**
     * @var ObjectValidator
     */
    protected  $validator;

    /**
     * @return ObjectValidator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    private function baseProperty() : ObjectValidator
    {
        return new ObjectValidator(
            (object) [
                //'type'        => new EnumValidator(),
                'order'       => new UnsignedIntegerValidator(),
                'title'       => new StringValidator(),
                'description' => new StringValidator(),
                'meta'        => new ObjectValidator(),
            ],
            ['type']
        );
    }

    public function __construct()
    {
        /**
         * enum
         */
        $enumProperty = self::baseProperty();
        $enumProperty->properties->type = new EnumValidator(['enum']);
        $enumProperty->properties->enum = new ArrayValidator(
            0,
            null,
            new StringValidator()
        );
        //todo set callback, must be one of enum values
        $enumProperty->properties->default = new StringValidator();
        $enumProperty->properties->meta    = new ObjectValidator(
            (object) [
                'display' => (new EnumValidator([
                    'platform',
                    'radio',
                    'select',
                    'stars'
                ])),
                'enum-titles' => (new ObjectValidator(
                    null,
                    [],
                    new StringValidator()
                ))/*->setValidateCallback(
                    function (Result $validateResult, BaseValidator $validator) {
                        //todo validate enum-titles keys are defined in enum
                        return $validateResult;
                    }
                )*/
            ]
        );
        $enumProperty->required = array_merge(
            $enumProperty->required,
            ['enum']
        );

        /**
         * number
         */
        $numberProperty = self::baseProperty();
        $numberProperty->properties->type    = new EnumValidator(['number']);
        $numberProperty->properties->minimum = new NumberValidator();
        $numberProperty->properties->maximum = new NumberValidator();
        $numberProperty->properties->default = new NumberValidator();

        /**
         * integer
         */
        $integerProperty = self::baseProperty();
        $integerProperty->properties->type    = new EnumValidator(['integer']);
        $integerProperty->properties->minimum = new IntegerValidator();
        $integerProperty->properties->maximum = new IntegerValidator();
        $integerProperty->properties->default = new IntegerValidator();

        /**
         * string
         */
        $stringProperty = self::baseProperty();
        $stringProperty->properties->type      = new EnumValidator(['string']);
        $stringProperty->properties->minLength = new UnsignedIntegerValidator();
        $stringProperty->properties->maxLength = new UnsignedIntegerValidator();
        $stringProperty->properties->default   = new IntegerValidator();

        /**
         * date
         */
        $dateProperty = self::baseProperty();
        $dateProperty->properties->type          = new EnumValidator(['date']);
        $dateProperty->properties->default       = new DateValidator();
        $dateProperty->properties->formatMinimum = new DateValidator();
        $dateProperty->properties->formatMinimum = new DateValidator();

        /**
         * date-time
         */
        $dateTimeProperty = self::baseProperty();
        $dateTimeProperty->properties->type          = new EnumValidator(['date-time']);
        $dateTimeProperty->properties->default       = new DatetimeValidator();
        $dateTimeProperty->properties->formatMinimum = new DatetimeValidator();
        $dateTimeProperty->properties->formatMinimum = new DatetimeValidator();

        /**
         * array
         */
        $arrayProperty = self::baseProperty();
        $arrayProperty->properties->type        = new EnumValidator(['array']);
        $arrayProperty->properties->minItems    = new UnsignedIntegerValidator();
        $arrayProperty->properties->maxItems    = new UnsignedIntegerValidator();
        $arrayProperty->properties->uniqueItems = new BooleanValidator();
        $arrayProperty->properties->items       = $enumProperty;
        $arrayProperty->required = array_merge(
            $arrayProperty->required,
            ['items']
        );


        file_put_contents('schema.json', $dateTimeProperty->toJSON(TRUE));

        $this->validator = new ObjectValidator(
            (object) [
                'type'       => new EnumValidator(['object']),
                'properties' => new ObjectValidator(
                    (object) [
                    ],
                    [],
                    new OneOf(
                        $arrayProperty,
                        $enumProperty,
                        $numberProperty,
                        $integerProperty,
                        $stringProperty,
                        $dateProperty,
                        $dateTimeProperty
                    )
                ),
                //todo add validation callback
                'required'   => (new ArrayValidator(
                    0,
                    null,
                    new StringValidator()
                ))->setValidateCallback(
                    function (Result $validateResult, BaseValidator $validator) {
                        return $validateResult;
                    }
                ),
                'title'       => new StringValidator(),
                'description' => new StringValidator(),
                'meta'        => new ObjectValidator(
                    (object) [
                        'show_title'                  => (new BooleanValidator())
                            ->setDefault(false),
                        'show_description'            => (new BooleanValidator())
                            ->setDefault(false),
                        'show_properties_title'       => (new BooleanValidator())
                            ->setDefault(true),
                        'show_properties_description' => (new BooleanValidator())
                            ->setDefault(false),
                        'show_in_groups_of'           => (new UnsignedIntegerValidator())
                            ->setDefault(1),
                        'submit_trigger'              => (new EnumValidator([
                            'platform',
                            'on_change',
                            'button'
                        ]))
                            ->setDefault('platform')
                    ],
                    [],
                    false
                ),
                //todo
                'x-visibility'  => (new ObjectValidator(
                ))->setValidateCallback(
                    function (Result $validateResult, BaseValidator $validator) {
                        //todo validate enum-titles keys are defined in enum
                        return $validateResult;
                    }
                ),
                'minProperties' => (new UnsignedIntegerValidator())
                    ->setDefault(0),
                'maxProperties' => (new UnsignedIntegerValidator())
                    ->setDefault(null)
            ],
            [
                'type',
                'properties'
            ],
            false
        );
    }
}

<?php

namespace Fromholdio\DropdownOptionAttrsField\Forms;

use SilverStripe\Core\Convert;
use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\FieldType\DBField;

class DropdownOptionAttrsField extends DropdownField
{
    protected $optionAttributes = [];

    protected function getFieldOption($value, $title)
    {
        $option = parent::getFieldOption($value, $title);
        $attributes = $this->getOptionAttributes($value);
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $parts[] = sprintf('%s="%s"', Convert::raw2att($key), Convert::raw2att($value));
            }
            $option->setField(
                'AttributesHTML',
                DBField::create_field('HTMLFragment', implode(' ', $parts))
            );
        }
        return $option;
    }

    public function setOptionsAttributes(array $optionsAttributesData): self
    {
        foreach ($optionsAttributesData as $value => $attributesData) {
            $this->setOptionAttributes($value, $attributesData);
        }
        return $this;
    }

    public function setOptionAttributes(mixed $value, array $attributesData): self
    {
        foreach ($attributesData as $attributeName => $attributeValue) {
            $this->setOptionAttribute($value, $attributeName, $attributeValue);
        }
        return $this;
    }

    public function setOptionAttribute(mixed $value, string $attributeName, ?string $attributeValue): self
    {
        if (is_null($attributeValue)) {
            unset($this->optionAttributes[$value][$attributeName]);
        } else {
            $this->optionAttributes[$value][$attributeName] = $attributeValue;
        }
        return $this;
    }

    protected function getOptionAttribute(mixed $value, string $attributeName): ?string
    {
        return $this->optionAttributes[$value][$attributeName] ?? null;
    }

    protected function getOptionAttributes(mixed $value): array
    {
        return $this->optionAttributes[$value] ?? [];
    }

    public function Type()
    {
        return 'dropdown ' . parent::Type();
    }
}

<?php

namespace Aslnbxrz\OneID;

final class OneIDUserLegalEntity
{
    public function __construct(
        public bool   $isBasic,
        public string $tin,
        public string $acronUz,
        public string $leTin,
        public string $leName,
    )
    {
    }

    public function isSelected(): bool
    {
        return $this->isBasic;
    }
}
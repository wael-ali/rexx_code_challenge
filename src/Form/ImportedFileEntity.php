<?php

namespace App\Form;

class ImportedFileEntity
{

    private string $jsonFile;

    public function getjsonFile(): ?string
    {
        return $this->jsonFile;
    }

    public function setjsonFile(string $jsonFile): self
    {
        $this->jsonFile = $jsonFile;

        return $this;
    }
}

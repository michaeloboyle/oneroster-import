<?php

namespace oat\OneRoster\Storage;

use Doctrine\Common\Collections\ArrayCollection;
use oat\OneRoster\Service\ImportService;

class CsvStorage implements StorageInterface
{
    /** @var ImportService */
    private $importService;

    /** @var array */
    private $imports;

    /**
     * CsvStorage constructor.
     * @param ImportService $importService
     */
    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * @param string $typeOfEntity [orgs,classes..]
     *
     * @return ArrayCollection
     * @throws \Exception
     */
    public function findByType($typeOfEntity)
    {
        if (isset($this->imports[$typeOfEntity])) {
            return $this->imports[$typeOfEntity];
        }

        $this->imports[$typeOfEntity] = $this->importService->import($this->importService->getPathToFolder() . $typeOfEntity . '.csv', $typeOfEntity);

        return $this->imports[$typeOfEntity];
    }

    /**
     * @param string $typeOfEntity [orgs,classes..]
     *
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function findByTypeAndId($typeOfEntity, $id)
    {
        if (isset($this->imports[$typeOfEntity])) {
            return $this->imports[$typeOfEntity]->get($id);
        }

        if (!isset($this->imports[$typeOfEntity])) {
            $this->imports[$typeOfEntity] = $this->importService->import($this->importService->getPathToFolder() . $typeOfEntity . '.csv', $typeOfEntity);
        }

        foreach ($this->imports[$typeOfEntity] as $item) {
            if ($item['sourcedId'] === $id) {
                return $item;
            }
        }
    }
}
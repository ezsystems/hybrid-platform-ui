<?php

namespace EzSystems\HybridPlatformUi\Dashboard;

class Dashboard
{
    /** @var SectionInterface[] */
    protected $sections = [];

    /**
     * @param string $identifier
     *
     * @return SectionInterface
     */
    public function getSection(string $identifier): SectionInterface
    {
        return $this->sections[$identifier];
    }

    /**
     * @return SectionInterface[]
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * @param string $identifier
     * @param SectionInterface $section
     */
    public function setSection(string $identifier, SectionInterface $section)
    {
        $this->sections[$identifier] = $section;
    }

    /**
     * @param array $sections
     */
    public function setSections(array $sections)
    {
        foreach ($sections as $identifier => $section) {
            $this->setSection($identifier, $section);
        }
    }
}

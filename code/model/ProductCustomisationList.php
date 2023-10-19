<?php

class ProductCustomisationList extends DataObject
{
    private static $db = array(
        "Title" => "Varchar"
    );

    private static $has_one = array(
        "SiteConfig" => "SiteConfig"
    );

    private static $has_many = array(
        "Customisations" => "ProductCustomisation"
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('Customisations');

        // Only add fields if the object exists
        if ($this->ID) {
            // Deal with customisations
            $add_button = new GridFieldAddNewButton('toolbar-header-left');
            $add_button->setButtonName(_t(
                "CustomisableProduct.AddCustomisation",
                "Add Customisation"
            ));

            $custom_config = GridFieldConfig::create()->addComponents(
                new GridFieldToolbarHeader(),
                $add_button,
                new GridFieldSortableHeader(),
                new GridFieldDataColumns(),
                new GridFieldPaginator(20),
                new GridFieldEditButton(),
                new GridFieldDeleteAction(),
                new GridFieldDetailForm(),
                new GridFieldOrderableRows('Sort')
            );

            $fields->addFieldToTab(
                'Root.Main',
                GridField::create(
                    'Customisations',
                    '',
                    $this->Customisations(),
                    $custom_config
                )
            );
        }

        return $fields;
    }

    public function onBeforeDelete() {
        parent::onBeforeDelete();

        // Clean up customisations
        foreach ($this->Customisations() as $customisation) {
            $customisation->delete();
        }
    }
}
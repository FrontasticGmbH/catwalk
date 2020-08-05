<?php

return array (
  'frontastic/boost/content/markdown' => 
  Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic::__set_state(array(
     'tasticId' => '18200b58ee129ce6aa463e682d0011f0',
     'tasticType' => 'frontastic/boost/content/markdown',
     'sequence' => '001593413435532861',
     'name' => 'Markdown',
     'description' => '',
     'configurationSchema' => 
    array (
      'tasticType' => 'frontastic/boost/content/markdown',
      'name' => 'Markdown',
      'category' => 'Content',
      'icon' => 'wrap_text',
      'schema' => 
      array (
        0 => 
        array (
          'name' => 'Content',
          'fields' => 
          array (
            0 => 
            array (
              'label' => 'Content',
              'field' => 'text',
              'type' => 'markdown',
              'default' => '* Enter
* some
* Markdown',
              'translatable' => true,
            ),
            1 => 
            array (
              'label' => 'Alignment',
              'field' => 'align',
              'type' => 'enum',
              'default' => 'left',
              'values' => 
              array (
                0 => 
                array (
                  'value' => 'left',
                  'name' => 'Left',
                ),
                1 => 
                array (
                  'value' => 'center',
                  'name' => 'Center',
                ),
                2 => 
                array (
                  'value' => 'right',
                  'name' => 'Right',
                ),
                3 => 
                array (
                  'value' => 'justify',
                  'name' => 'Justify',
                ),
              ),
            ),
            2 => 
            array (
              'label' => 'Padding',
              'field' => 'padding',
              'type' => 'enum',
              'default' => 'none',
              'values' => 
              array (
                0 => 
                array (
                  'value' => 'none',
                  'name' => 'No Padding',
                ),
                1 => 
                array (
                  'value' => 'small',
                  'name' => 'Small',
                ),
                2 => 
                array (
                  'value' => 'middle',
                  'name' => 'Middle',
                ),
                3 => 
                array (
                  'value' => 'large',
                  'name' => 'Large',
                ),
              ),
            ),
          ),
        ),
      ),
    ),
     'environment' => NULL,
     'metaData' => 
    array (
      '_type' => 'Frontastic\\Backstage\\UserBundle\\Domain\\MetaData',
      'author' => 'system@frontastic.io',
      'changed' => '2020-06-29T08:50:35+02:00',
    ),
     'isDeleted' => false,
  )),
  'frontastic/boost/content/feature-service' => 
  Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic::__set_state(array(
     'tasticId' => '18200b58ee129ce6aa463e682d001d35',
     'tasticType' => 'frontastic/boost/content/feature-service',
     'sequence' => '001593413435564919',
     'name' => 'Feature service',
     'description' => '',
     'configurationSchema' => 
    array (
      'tasticType' => 'frontastic/boost/content/feature-service',
      'name' => 'Feature service',
      'category' => 'Content',
      'icon' => 'menu',
      'schema' => 
      array (
      ),
    ),
     'environment' => NULL,
     'metaData' => 
    array (
      '_type' => 'Frontastic\\Backstage\\UserBundle\\Domain\\MetaData',
      'author' => 'system@frontastic.io',
      'changed' => '2020-06-29T08:50:35+02:00',
    ),
     'isDeleted' => false,
  )),
  'frontastic/boost/header/main-menu' => 
  Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic::__set_state(array(
     'tasticId' => '18200b58ee129ce6aa463e682d00281f',
     'tasticType' => 'frontastic/boost/header/main-menu',
     'sequence' => '001593413436602540',
     'name' => 'Main Menu',
     'description' => '',
     'configurationSchema' => 
    array (
      'tasticType' => 'frontastic/boost/header/main-menu',
      'name' => 'Main Menu',
      'icon' => 'menu',
      'category' => 'Header',
      'schema' => 
      array (
        0 => 
        array (
          'name' => 'Menu',
          'fields' => 
          array (
            0 => 
            array (
              'type' => 'description',
              'text' => 'Choose a few top categories to be displayed on top of the navigation. Selecting a top category will allow the consumer to navigate to its tree.',
            ),
            1 => 
            array (
              'label' => 'Top Categories',
              'field' => 'topCategories',
              'type' => 'group',
              'itemLabelField' => 'name',
              'fields' => 
              array (
                0 => 
                array (
                  'label' => 'Name',
                  'field' => 'name',
                  'translatable' => false,
                  'required' => true,
                  'type' => 'string',
                ),
                1 => 
                array (
                  'label' => 'Navigation Tree',
                  'field' => 'tree',
                  'type' => 'tree',
                ),
                2 => 
                array (
                  'label' => 'Link',
                  'field' => 'reference',
                  'required' => true,
                  'type' => 'reference',
                ),
                3 => 
                array (
                  'label' => 'Mobile Nav Background',
                  'field' => 'mobileNavBackgroundImage',
                  'type' => 'media',
                ),
                4 => 
                array (
                  'type' => 'description',
                  'text' => 'Ratio-Setting will currently not be used.',
                ),
              ),
            ),
          ),
        ),
        1 => 
        array (
          'name' => 'Logo',
          'fields' => 
          array (
            0 => 
            array (
              'type' => 'description',
              'text' => 'Select a logo to overwrite the default \'Catwalk\'.',
            ),
            1 => 
            array (
              'label' => 'Logo',
              'field' => 'logo',
              'type' => 'media',
              'required' => false,
            ),
          ),
        ),
      ),
    ),
     'environment' => NULL,
     'metaData' => 
    array (
      '_type' => 'Frontastic\\Backstage\\UserBundle\\Domain\\MetaData',
      'author' => 'system@frontastic.io',
      'changed' => '2020-06-29T08:50:36+02:00',
    ),
     'isDeleted' => false,
  )),
  'frontastic/boost/forms/newsletter-block' => 
  Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic::__set_state(array(
     'tasticId' => '18200b58ee129ce6aa463e682d00334e',
     'tasticType' => 'frontastic/boost/forms/newsletter-block',
     'sequence' => '001593413435134532',
     'name' => 'Newsletter',
     'description' => '',
     'configurationSchema' => 
    array (
      'tasticType' => 'frontastic/boost/forms/newsletter-block',
      'name' => 'Newsletter',
      'icon' => 'menu',
      'category' => 'Forms',
      'schema' => 
      array (
        0 => 
        array (
          'name' => 'Options',
          'fields' => 
          array (
          ),
        ),
      ),
    ),
     'environment' => NULL,
     'metaData' => 
    array (
      '_type' => 'Frontastic\\Backstage\\UserBundle\\Domain\\MetaData',
      'author' => 'system@frontastic.io',
      'changed' => '2020-06-29T08:50:35+02:00',
    ),
     'isDeleted' => false,
  )),
  'frontastic/boost/footer' => 
  Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic::__set_state(array(
     'tasticId' => '18200b58ee129ce6aa463e682d004551',
     'tasticType' => 'frontastic/boost/footer',
     'sequence' => '001593413436459798',
     'name' => 'Footer',
     'description' => '',
     'configurationSchema' => 
    array (
      'tasticType' => 'frontastic/boost/footer',
      'name' => 'Footer',
      'icon' => 'list',
      'category' => 'Footer',
      'schema' => 
      array (
        0 => 
        array (
          'name' => 'Meta Navigation',
          'fields' => 
          array (
            0 => 
            array (
              'label' => 'Title',
              'field' => 'title',
              'type' => 'string',
            ),
            1 => 
            array (
              'label' => 'Links',
              'field' => 'links',
              'type' => 'group',
              'itemLabelField' => 'label',
              'fields' => 
              array (
                0 => 
                array (
                  'label' => 'Label',
                  'field' => 'label',
                  'type' => 'string',
                ),
                1 => 
                array (
                  'label' => 'Link',
                  'field' => 'reference',
                  'type' => 'reference',
                ),
              ),
            ),
          ),
        ),
        1 => 
        array (
          'name' => 'Help & Information Links',
          'fields' => 
          array (
            0 => 
            array (
              'label' => 'Header',
              'field' => 'infoHeader',
              'type' => 'string',
            ),
            1 => 
            array (
              'label' => 'Header icon',
              'field' => 'infoHeaderIcon',
              'translatable' => false,
              'type' => 'enum',
              'required' => false,
              'values' => 
              array (
                0 => 
                array (
                  'value' => 'help',
                  'name' => 'Help',
                ),
                1 => 
                array (
                  'value' => 'chat',
                  'name' => 'Chat',
                ),
                2 => 
                array (
                  'value' => 'announcement',
                  'name' => 'Announcement',
                ),
              ),
            ),
            2 => 
            array (
              'label' => 'Links',
              'field' => 'infoLinks',
              'type' => 'group',
              'itemLabelField' => 'label',
              'fields' => 
              array (
                0 => 
                array (
                  'label' => 'Label',
                  'field' => 'label',
                  'type' => 'string',
                ),
                1 => 
                array (
                  'label' => 'Link',
                  'field' => 'reference',
                  'type' => 'reference',
                ),
              ),
            ),
          ),
        ),
        2 => 
        array (
          'name' => 'Contact Info',
          'fields' => 
          array (
            0 => 
            array (
              'label' => 'Header',
              'field' => 'contacHeader',
              'type' => 'string',
            ),
            1 => 
            array (
              'label' => 'Phone Number',
              'field' => 'phoneNumber',
              'type' => 'string',
            ),
            2 => 
            array (
              'label' => 'Phone Number Subline',
              'field' => 'phoneNumberSubline',
              'type' => 'string',
            ),
            3 => 
            array (
              'label' => 'E-Mail',
              'field' => 'email',
              'type' => 'string',
            ),
            4 => 
            array (
              'label' => 'E-Mail Overline',
              'field' => 'emailOverline',
              'type' => 'string',
            ),
          ),
        ),
        3 => 
        array (
          'name' => 'About Links',
          'fields' => 
          array (
            0 => 
            array (
              'label' => 'About Header',
              'field' => 'aboutHeader',
              'type' => 'string',
            ),
            1 => 
            array (
              'label' => 'Header icon',
              'field' => 'aboutHeaderIcon',
              'translatable' => false,
              'type' => 'enum',
              'required' => false,
              'values' => 
              array (
                0 => 
                array (
                  'value' => 'help',
                  'name' => 'Help',
                ),
                1 => 
                array (
                  'value' => 'chat',
                  'name' => 'Chat',
                ),
                2 => 
                array (
                  'value' => 'announcement',
                  'name' => 'Announcement',
                ),
              ),
            ),
            2 => 
            array (
              'label' => 'Links',
              'field' => 'aboutLinks',
              'type' => 'group',
              'itemLabelField' => 'label',
              'fields' => 
              array (
                0 => 
                array (
                  'label' => 'Label',
                  'field' => 'label',
                  'type' => 'string',
                ),
                1 => 
                array (
                  'label' => 'Link',
                  'field' => 'reference',
                  'type' => 'reference',
                ),
              ),
            ),
          ),
        ),
        4 => 
        array (
          'name' => 'Payment Methods',
          'fields' => 
          array (
            0 => 
            array (
              'label' => 'Payment Methods',
              'field' => 'paymentMethods',
              'type' => 'group',
              'itemLabelField' => 'icon',
              'fields' => 
              array (
                0 => 
                array (
                  'label' => 'Icon',
                  'field' => 'playmentIcon',
                  'type' => 'enum',
                  'values' => 
                  array (
                    0 => 
                    array (
                      'name' => 'PayPal',
                      'value' => 'paypal',
                    ),
                    1 => 
                    array (
                      'name' => 'Visa',
                      'value' => 'visa',
                    ),
                    2 => 
                    array (
                      'name' => 'Mastercard',
                      'value' => 'mastercard',
                    ),
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    ),
     'environment' => NULL,
     'metaData' => 
    array (
      '_type' => 'Frontastic\\Backstage\\UserBundle\\Domain\\MetaData',
      'author' => 'system@frontastic.io',
      'changed' => '2020-06-29T08:50:36+02:00',
    ),
     'isDeleted' => false,
  )),
  'frontastic/boost/product/product-details' => 
  Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic::__set_state(array(
     'tasticId' => '18200b58ee129ce6aa463e682d004b09',
     'tasticType' => 'frontastic/boost/product/product-details',
     'sequence' => '001593413435911030',
     'name' => 'Product Details',
     'description' => '',
     'configurationSchema' => 
    array (
      'tasticType' => 'frontastic/boost/product/product-details',
      'name' => 'Product Details',
      'category' => 'Product Details',
      'icon' => 'card_giftcard',
      'schema' => 
      array (
        0 => 
        array (
          'name' => 'Main Configuration',
          'fields' => 
          array (
            0 => 
            array (
              'label' => 'Stream',
              'field' => 'stream',
              'type' => 'stream',
              'streamType' => 'product',
              'default' => NULL,
            ),
          ),
        ),
      ),
    ),
     'environment' => NULL,
     'metaData' => 
    array (
      '_type' => 'Frontastic\\Backstage\\UserBundle\\Domain\\MetaData',
      'author' => 'system@frontastic.io',
      'changed' => '2020-06-29T08:50:35+02:00',
    ),
     'isDeleted' => false,
  )),
  'toby-account-orders' => 
  Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic::__set_state(array(
     'tasticId' => '514f3658e3700306a6f63fdea2032024',
     'tasticType' => 'toby-account-orders',
     'sequence' => '001593502042877056',
     'name' => 'Orders',
     'description' => '',
     'configurationSchema' => 
    array (
      'tasticType' => 'toby-account-orders',
      'name' => 'Orders',
      'category' => 'Account',
      'icon' => 'add_shopping_cart',
      'schema' => 
      array (
        0 => 
        array (
          'name' => 'Content',
          'fields' => 
          array (
            0 => 
            array (
              'label' => 'Orders Group',
              'field' => 'ordersGroup',
              'type' => 'group',
              'itemLabelField' => 'labeling',
              'fields' => 
              array (
                0 => 
                array (
                  'label' => 'Lablablab',
                  'field' => 'labeling',
                  'type' => 'string',
                ),
                1 => 
                array (
                  'label' => 'Orders',
                  'field' => 'orders',
                  'type' => 'stream',
                  'streamType' => 'account-orders',
                ),
              ),
            ),
          ),
        ),
      ),
    ),
     'environment' => NULL,
     'metaData' => 
    array (
      '_type' => 'Frontastic\\Backstage\\UserBundle\\Domain\\MetaData',
      'author' => 'dev@frontastic.io',
      'changed' => '2020-06-30T09:27:22+02:00',
    ),
     'isDeleted' => false,
  ))
);

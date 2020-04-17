.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt



.. _configuration:

Configuration
-------------

Make model loggable:
   The interface provided with this extension is the only thing you need to implement::

      <?php

      declare(strict_types=1);

      namespace WORKSHOP\WorkshopBlog\Domain\Model;

      use SUDHAUS7\FeDataHistory\Domain\HistoryEntityInterface;
      use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

      /**
       * Class Blog
       * @package MYVENDOR\ExampleBlog\Domain\Model
       */
      class Blog extends AbstractEntity implements HistoryEntityInterface
      { }

Usage
-----

Write your Fluid form and let your frontend users edit as logged in users.
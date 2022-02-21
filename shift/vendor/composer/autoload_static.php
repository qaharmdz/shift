<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitec3ddbcfb6fb1e1b644ccfec9082eb1e
{
    public static $files = array (
        'd767e4fc2dc52fe66584ab8c6684783e' => __DIR__ . '/..' . '/adbario/php-dot-notation/src/helpers.php',
        '3917c79c5052b270641b5a200963dbc2' => __DIR__ . '/..' . '/kint-php/kint/init.php',
        '5dedf103d98752dd5c995cc0a98a887c' => __DIR__ . '/../../..' . '/shift/system/helper/general.php',
        '6e58ef87048f90f0d9c15812842a6df3' => __DIR__ . '/../../..' . '/shift/system/helper/utf8.php',
    );

    public static $prefixLengthsPsr4 = array (
        'K' => 
        array (
            'Kint\\' => 5,
        ),
        'A' => 
        array (
            'Adbar\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Kint\\' => 
        array (
            0 => __DIR__ . '/..' . '/kint-php/kint/src',
        ),
        'Adbar\\' => 
        array (
            0 => __DIR__ . '/..' . '/adbario/php-dot-notation/src',
        ),
    );

    public static $classMap = array (
        'Action' => __DIR__ . '/../../..' . '/shift/system/engine/action.php',
        'Adbar\\Dot' => __DIR__ . '/..' . '/adbario/php-dot-notation/src/Dot.php',
        'Cache' => __DIR__ . '/../../..' . '/shift/system/library/cache.php',
        'Cache\\APC' => __DIR__ . '/../../..' . '/shift/system/library/cache/apc.php',
        'Cache\\File' => __DIR__ . '/../../..' . '/shift/system/library/cache/file.php',
        'Cache\\Mem' => __DIR__ . '/../../..' . '/shift/system/library/cache/mem.php',
        'Cart\\User' => __DIR__ . '/../../..' . '/shift/system/library/cart/user.php',
        'ComposerAutoloaderInitec3ddbcfb6fb1e1b644ccfec9082eb1e' => __DIR__ . '/..' . '/composer/autoload_real.php',
        'Composer\\Autoload\\ClassLoader' => __DIR__ . '/..' . '/composer/ClassLoader.php',
        'Composer\\Autoload\\ComposerStaticInitec3ddbcfb6fb1e1b644ccfec9082eb1e' => __DIR__ . '/..' . '/composer/autoload_static.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Controller' => __DIR__ . '/../../..' . '/shift/system/engine/controller.php',
        'ControllerAccountAccount' => __DIR__ . '/../../..' . '/shift/catalog/controller/account/account.php',
        'ControllerAccountEdit' => __DIR__ . '/../../..' . '/shift/catalog/controller/account/edit.php',
        'ControllerAccountForgotten' => __DIR__ . '/../../..' . '/shift/catalog/controller/account/forgotten.php',
        'ControllerAccountLogin' => __DIR__ . '/../../..' . '/shift/catalog/controller/account/login.php',
        'ControllerAccountLogout' => __DIR__ . '/../../..' . '/shift/catalog/controller/account/logout.php',
        'ControllerAccountPassword' => __DIR__ . '/../../..' . '/shift/catalog/controller/account/password.php',
        'ControllerAccountRegister' => __DIR__ . '/../../..' . '/shift/catalog/controller/account/register.php',
        'ControllerAccountReset' => __DIR__ . '/../../..' . '/shift/catalog/controller/account/reset.php',
        'ControllerAccountSuccess' => __DIR__ . '/../../..' . '/shift/catalog/controller/account/success.php',
        'ControllerCatalogInformation' => __DIR__ . '/../../..' . '/shift/admin/controller/catalog/information.php',
        'ControllerCommonColumnLeft' => __DIR__ . '/../../..' . '/shift/admin/controller/common/column_left.php',
        'ControllerCommonColumnRight' => __DIR__ . '/../../..' . '/shift/catalog/controller/common/column_right.php',
        'ControllerCommonContentBottom' => __DIR__ . '/../../..' . '/shift/catalog/controller/common/content_bottom.php',
        'ControllerCommonContentTop' => __DIR__ . '/../../..' . '/shift/catalog/controller/common/content_top.php',
        'ControllerCommonDashboard' => __DIR__ . '/../../..' . '/shift/admin/controller/common/dashboard.php',
        'ControllerCommonFileManager' => __DIR__ . '/../../..' . '/shift/admin/controller/common/filemanager.php',
        'ControllerCommonFooter' => __DIR__ . '/../../..' . '/shift/admin/controller/common/footer.php',
        'ControllerCommonForgotten' => __DIR__ . '/../../..' . '/shift/admin/controller/common/forgotten.php',
        'ControllerCommonHeader' => __DIR__ . '/../../..' . '/shift/admin/controller/common/header.php',
        'ControllerCommonHome' => __DIR__ . '/../../..' . '/shift/catalog/controller/common/home.php',
        'ControllerCommonLanguage' => __DIR__ . '/../../..' . '/shift/catalog/controller/common/language.php',
        'ControllerCommonLogin' => __DIR__ . '/../../..' . '/shift/admin/controller/common/login.php',
        'ControllerCommonLogout' => __DIR__ . '/../../..' . '/shift/admin/controller/common/logout.php',
        'ControllerCommonMaintenance' => __DIR__ . '/../../..' . '/shift/catalog/controller/common/maintenance.php',
        'ControllerCommonReset' => __DIR__ . '/../../..' . '/shift/admin/controller/common/reset.php',
        'ControllerCommonSearch' => __DIR__ . '/../../..' . '/shift/catalog/controller/common/search.php',
        'ControllerDesignBanner' => __DIR__ . '/../../..' . '/shift/admin/controller/design/banner.php',
        'ControllerDesignLanguage' => __DIR__ . '/../../..' . '/shift/admin/controller/design/language.php',
        'ControllerDesignLayout' => __DIR__ . '/../../..' . '/shift/admin/controller/design/layout.php',
        'ControllerErrorNotFound' => __DIR__ . '/../../..' . '/shift/admin/controller/error/not_found.php',
        'ControllerErrorPermission' => __DIR__ . '/../../..' . '/shift/admin/controller/error/permission.php',
        'ControllerEventDebug' => __DIR__ . '/../../..' . '/shift/catalog/controller/event/debug.php',
        'ControllerEventTheme' => __DIR__ . '/../../..' . '/shift/admin/controller/event/theme.php',
        'ControllerEventTranslation' => __DIR__ . '/../../..' . '/shift/catalog/controller/event/translation.php',
        'ControllerExtensionDashboardMap' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/dashboard/map.php',
        'ControllerExtensionDashboardOnline' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/dashboard/online.php',
        'ControllerExtensionEvent' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/event.php',
        'ControllerExtensionExtension' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/extension.php',
        'ControllerExtensionExtensionDashboard' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/extension/dashboard.php',
        'ControllerExtensionExtensionModule' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/extension/module.php',
        'ControllerExtensionExtensionTheme' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/extension/theme.php',
        'ControllerExtensionInstaller' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/installer.php',
        'ControllerExtensionModuleAccount' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/module/account.php',
        'ControllerExtensionModuleBanner' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/module/banner.php',
        'ControllerExtensionModuleCarousel' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/module/carousel.php',
        'ControllerExtensionModuleHTML' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/module/html.php',
        'ControllerExtensionModuleInformation' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/module/information.php',
        'ControllerExtensionModuleSlideshow' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/module/slideshow.php',
        'ControllerExtensionModuleStore' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/module/store.php',
        'ControllerExtensionThemeThemeDefault' => __DIR__ . '/../../..' . '/shift/admin/controller/extension/theme/theme_default.php',
        'ControllerInformationContact' => __DIR__ . '/../../..' . '/shift/catalog/controller/information/contact.php',
        'ControllerInformationInformation' => __DIR__ . '/../../..' . '/shift/catalog/controller/information/information.php',
        'ControllerInformationSitemap' => __DIR__ . '/../../..' . '/shift/catalog/controller/information/sitemap.php',
        'ControllerInstallStep1' => __DIR__ . '/../../..' . '/shift/install/controller/install/step_1.php',
        'ControllerInstallStep2' => __DIR__ . '/../../..' . '/shift/install/controller/install/step_2.php',
        'ControllerInstallStep3' => __DIR__ . '/../../..' . '/shift/install/controller/install/step_3.php',
        'ControllerInstallStep4' => __DIR__ . '/../../..' . '/shift/install/controller/install/step_4.php',
        'ControllerLocalisationLanguage' => __DIR__ . '/../../..' . '/shift/admin/controller/localisation/language.php',
        'ControllerSettingSetting' => __DIR__ . '/../../..' . '/shift/admin/controller/setting/setting.php',
        'ControllerSettingStore' => __DIR__ . '/../../..' . '/shift/admin/controller/setting/store.php',
        'ControllerStartupCompatibility' => __DIR__ . '/../../..' . '/shift/admin/controller/startup/compatibility.php',
        'ControllerStartupDatabase' => __DIR__ . '/../../..' . '/shift/install/controller/startup/database.php',
        'ControllerStartupError' => __DIR__ . '/../../..' . '/shift/admin/controller/startup/error.php',
        'ControllerStartupEvent' => __DIR__ . '/../../..' . '/shift/admin/controller/startup/event.php',
        'ControllerStartupLanguage' => __DIR__ . '/../../..' . '/shift/install/controller/startup/language.php',
        'ControllerStartupLogin' => __DIR__ . '/../../..' . '/shift/admin/controller/startup/login.php',
        'ControllerStartupMaintenance' => __DIR__ . '/../../..' . '/shift/catalog/controller/startup/maintenance.php',
        'ControllerStartupPermission' => __DIR__ . '/../../..' . '/shift/admin/controller/startup/permission.php',
        'ControllerStartupRouter' => __DIR__ . '/../../..' . '/shift/admin/controller/startup/router.php',
        'ControllerStartupSeoUrl' => __DIR__ . '/../../..' . '/shift/catalog/controller/startup/seo_url.php',
        'ControllerStartupSession' => __DIR__ . '/../../..' . '/shift/catalog/controller/startup/session.php',
        'ControllerStartupStartup' => __DIR__ . '/../../..' . '/shift/admin/controller/startup/startup.php',
        'ControllerStartupUpgrade' => __DIR__ . '/../../..' . '/shift/install/controller/startup/upgrade.php',
        'ControllerToolBackup' => __DIR__ . '/../../..' . '/shift/admin/controller/tool/backup.php',
        'ControllerToolLog' => __DIR__ . '/../../..' . '/shift/admin/controller/tool/log.php',
        'ControllerToolUpload' => __DIR__ . '/../../..' . '/shift/admin/controller/tool/upload.php',
        'ControllerUpgradeUpgrade' => __DIR__ . '/../../..' . '/shift/install/controller/upgrade/upgrade.php',
        'ControllerUserUser' => __DIR__ . '/../../..' . '/shift/admin/controller/user/user.php',
        'ControllerUserUserPermission' => __DIR__ . '/../../..' . '/shift/admin/controller/user/user_permission.php',
        'DB' => __DIR__ . '/../../..' . '/shift/system/library/db.php',
        'DB\\MySQLi' => __DIR__ . '/../../..' . '/shift/system/library/db/mysqli.php',
        'Document' => __DIR__ . '/../../..' . '/shift/system/library/document.php',
        'Event' => __DIR__ . '/../../..' . '/shift/system/engine/event.php',
        'Front' => __DIR__ . '/../../..' . '/shift/system/engine/front.php',
        'Image' => __DIR__ . '/../../..' . '/shift/system/library/image.php',
        'Kint\\CallFinder' => __DIR__ . '/..' . '/kint-php/kint/src/CallFinder.php',
        'Kint\\Kint' => __DIR__ . '/..' . '/kint-php/kint/src/Kint.php',
        'Kint\\Parser\\ArrayLimitPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/ArrayLimitPlugin.php',
        'Kint\\Parser\\ArrayObjectPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/ArrayObjectPlugin.php',
        'Kint\\Parser\\Base64Plugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/Base64Plugin.php',
        'Kint\\Parser\\BinaryPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/BinaryPlugin.php',
        'Kint\\Parser\\BlacklistPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/BlacklistPlugin.php',
        'Kint\\Parser\\ClassMethodsPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/ClassMethodsPlugin.php',
        'Kint\\Parser\\ClassStaticsPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/ClassStaticsPlugin.php',
        'Kint\\Parser\\ClosurePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/ClosurePlugin.php',
        'Kint\\Parser\\ColorPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/ColorPlugin.php',
        'Kint\\Parser\\DOMDocumentPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/DOMDocumentPlugin.php',
        'Kint\\Parser\\DateTimePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/DateTimePlugin.php',
        'Kint\\Parser\\FsPathPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/FsPathPlugin.php',
        'Kint\\Parser\\IteratorPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/IteratorPlugin.php',
        'Kint\\Parser\\JsonPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/JsonPlugin.php',
        'Kint\\Parser\\MicrotimePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/MicrotimePlugin.php',
        'Kint\\Parser\\MysqliPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/MysqliPlugin.php',
        'Kint\\Parser\\Parser' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/Parser.php',
        'Kint\\Parser\\Plugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/Plugin.php',
        'Kint\\Parser\\ProxyPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/ProxyPlugin.php',
        'Kint\\Parser\\SerializePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/SerializePlugin.php',
        'Kint\\Parser\\SimpleXMLElementPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/SimpleXMLElementPlugin.php',
        'Kint\\Parser\\SplFileInfoPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/SplFileInfoPlugin.php',
        'Kint\\Parser\\SplObjectStoragePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/SplObjectStoragePlugin.php',
        'Kint\\Parser\\StreamPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/StreamPlugin.php',
        'Kint\\Parser\\TablePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/TablePlugin.php',
        'Kint\\Parser\\ThrowablePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/ThrowablePlugin.php',
        'Kint\\Parser\\TimestampPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/TimestampPlugin.php',
        'Kint\\Parser\\ToStringPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/ToStringPlugin.php',
        'Kint\\Parser\\TracePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/TracePlugin.php',
        'Kint\\Parser\\XmlPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Parser/XmlPlugin.php',
        'Kint\\Renderer\\CliRenderer' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/CliRenderer.php',
        'Kint\\Renderer\\PlainRenderer' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/PlainRenderer.php',
        'Kint\\Renderer\\Renderer' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Renderer.php',
        'Kint\\Renderer\\RichRenderer' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/RichRenderer.php',
        'Kint\\Renderer\\Rich\\ArrayLimitPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/ArrayLimitPlugin.php',
        'Kint\\Renderer\\Rich\\BinaryPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/BinaryPlugin.php',
        'Kint\\Renderer\\Rich\\BlacklistPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/BlacklistPlugin.php',
        'Kint\\Renderer\\Rich\\CallablePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/CallablePlugin.php',
        'Kint\\Renderer\\Rich\\ClosurePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/ClosurePlugin.php',
        'Kint\\Renderer\\Rich\\ColorPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/ColorPlugin.php',
        'Kint\\Renderer\\Rich\\DepthLimitPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/DepthLimitPlugin.php',
        'Kint\\Renderer\\Rich\\DocstringPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/DocstringPlugin.php',
        'Kint\\Renderer\\Rich\\MicrotimePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/MicrotimePlugin.php',
        'Kint\\Renderer\\Rich\\Plugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/Plugin.php',
        'Kint\\Renderer\\Rich\\PluginInterface' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/PluginInterface.php',
        'Kint\\Renderer\\Rich\\RecursionPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/RecursionPlugin.php',
        'Kint\\Renderer\\Rich\\SimpleXMLElementPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/SimpleXMLElementPlugin.php',
        'Kint\\Renderer\\Rich\\SourcePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/SourcePlugin.php',
        'Kint\\Renderer\\Rich\\TabPluginInterface' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/TabPluginInterface.php',
        'Kint\\Renderer\\Rich\\TablePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/TablePlugin.php',
        'Kint\\Renderer\\Rich\\TimestampPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/TimestampPlugin.php',
        'Kint\\Renderer\\Rich\\TraceFramePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/TraceFramePlugin.php',
        'Kint\\Renderer\\Rich\\ValuePluginInterface' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Rich/ValuePluginInterface.php',
        'Kint\\Renderer\\TextRenderer' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/TextRenderer.php',
        'Kint\\Renderer\\Text\\ArrayLimitPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Text/ArrayLimitPlugin.php',
        'Kint\\Renderer\\Text\\BlacklistPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Text/BlacklistPlugin.php',
        'Kint\\Renderer\\Text\\DepthLimitPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Text/DepthLimitPlugin.php',
        'Kint\\Renderer\\Text\\MicrotimePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Text/MicrotimePlugin.php',
        'Kint\\Renderer\\Text\\Plugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Text/Plugin.php',
        'Kint\\Renderer\\Text\\RecursionPlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Text/RecursionPlugin.php',
        'Kint\\Renderer\\Text\\TracePlugin' => __DIR__ . '/..' . '/kint-php/kint/src/Renderer/Text/TracePlugin.php',
        'Kint\\Utils' => __DIR__ . '/..' . '/kint-php/kint/src/Utils.php',
        'Kint\\Zval\\BlobValue' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/BlobValue.php',
        'Kint\\Zval\\ClosureValue' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/ClosureValue.php',
        'Kint\\Zval\\DateTimeValue' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/DateTimeValue.php',
        'Kint\\Zval\\InstanceValue' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/InstanceValue.php',
        'Kint\\Zval\\MethodValue' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/MethodValue.php',
        'Kint\\Zval\\ParameterValue' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/ParameterValue.php',
        'Kint\\Zval\\Representation\\ColorRepresentation' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/Representation/ColorRepresentation.php',
        'Kint\\Zval\\Representation\\DocstringRepresentation' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/Representation/DocstringRepresentation.php',
        'Kint\\Zval\\Representation\\MicrotimeRepresentation' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/Representation/MicrotimeRepresentation.php',
        'Kint\\Zval\\Representation\\Representation' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/Representation/Representation.php',
        'Kint\\Zval\\Representation\\SourceRepresentation' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/Representation/SourceRepresentation.php',
        'Kint\\Zval\\Representation\\SplFileInfoRepresentation' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/Representation/SplFileInfoRepresentation.php',
        'Kint\\Zval\\ResourceValue' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/ResourceValue.php',
        'Kint\\Zval\\SimpleXMLElementValue' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/SimpleXMLElementValue.php',
        'Kint\\Zval\\StreamValue' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/StreamValue.php',
        'Kint\\Zval\\ThrowableValue' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/ThrowableValue.php',
        'Kint\\Zval\\TraceFrameValue' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/TraceFrameValue.php',
        'Kint\\Zval\\TraceValue' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/TraceValue.php',
        'Kint\\Zval\\Value' => __DIR__ . '/..' . '/kint-php/kint/src/Zval/Value.php',
        'Language' => __DIR__ . '/../../..' . '/shift/system/library/language.php',
        'Loader' => __DIR__ . '/../../..' . '/shift/system/engine/loader.php',
        'Log' => __DIR__ . '/../../..' . '/shift/system/library/log.php',
        'Mail' => __DIR__ . '/../../..' . '/shift/system/library/mail.php',
        'Model' => __DIR__ . '/../../..' . '/shift/system/engine/model.php',
        'ModelCatalogInformation' => __DIR__ . '/../../..' . '/shift/admin/model/catalog/information.php',
        'ModelCatalogUrlAlias' => __DIR__ . '/../../..' . '/shift/admin/model/catalog/url_alias.php',
        'ModelDesignBanner' => __DIR__ . '/../../..' . '/shift/admin/model/design/banner.php',
        'ModelDesignLanguage' => __DIR__ . '/../../..' . '/shift/admin/model/design/language.php',
        'ModelDesignLayout' => __DIR__ . '/../../..' . '/shift/admin/model/design/layout.php',
        'ModelExtensionEvent' => __DIR__ . '/../../..' . '/shift/admin/model/extension/event.php',
        'ModelExtensionExtension' => __DIR__ . '/../../..' . '/shift/admin/model/extension/extension.php',
        'ModelExtensionModule' => __DIR__ . '/../../..' . '/shift/admin/model/extension/module.php',
        'ModelInstallInstall' => __DIR__ . '/../../..' . '/shift/install/model/install/install.php',
        'ModelLocalisationLanguage' => __DIR__ . '/../../..' . '/shift/admin/model/localisation/language.php',
        'ModelSettingSetting' => __DIR__ . '/../../..' . '/shift/admin/model/setting/setting.php',
        'ModelSettingStore' => __DIR__ . '/../../..' . '/shift/admin/model/setting/store.php',
        'ModelToolBackup' => __DIR__ . '/../../..' . '/shift/admin/model/tool/backup.php',
        'ModelToolImage' => __DIR__ . '/../../..' . '/shift/admin/model/tool/image.php',
        'ModelToolUpload' => __DIR__ . '/../../..' . '/shift/admin/model/tool/upload.php',
        'ModelUserUser' => __DIR__ . '/../../..' . '/shift/admin/model/user/user.php',
        'ModelUserUserGroup' => __DIR__ . '/../../..' . '/shift/admin/model/user/user_group.php',
        'Pagination' => __DIR__ . '/../../..' . '/shift/system/library/pagination.php',
        'Proxy' => __DIR__ . '/../../..' . '/shift/system/engine/proxy.php',
        'Registry' => __DIR__ . '/../../..' . '/shift/system/engine/registry.php',
        'Request' => __DIR__ . '/../../..' . '/shift/system/library/request.php',
        'Response' => __DIR__ . '/../../..' . '/shift/system/library/response.php',
        'Session' => __DIR__ . '/../../..' . '/shift/system/library/session.php',
        'Session\\DB' => __DIR__ . '/../../..' . '/shift/system/library/session/db.php',
        'Session\\File' => __DIR__ . '/../../..' . '/shift/system/library/session/file.php',
        'Session\\Native' => __DIR__ . '/../../..' . '/shift/system/library/session/native.php',
        'Shift\\System\\Core\\Autoload\\ClassLoader' => __DIR__ . '/../../..' . '/shift/system/core/autoload/classloader.php',
        'Shift\\System\\Core\\Autoload\\Psr4Lower' => __DIR__ . '/../../..' . '/shift/system/core/autoload/psr4lower.php',
        'Shift\\System\\Core\\Bags' => __DIR__ . '/../../..' . '/shift/system/core/bags.php',
        'Shift\\System\\Framework' => __DIR__ . '/../../..' . '/shift/system/framework.php',
        'Shift\\System\\core\\Config' => __DIR__ . '/../../..' . '/shift/system/core/config.php',
        'Template' => __DIR__ . '/../../..' . '/shift/system/library/template.php',
        'Template\\PHP' => __DIR__ . '/../../..' . '/shift/system/library/template/php.php',
        'Url' => __DIR__ . '/../../..' . '/shift/system/library/url.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitec3ddbcfb6fb1e1b644ccfec9082eb1e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitec3ddbcfb6fb1e1b644ccfec9082eb1e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitec3ddbcfb6fb1e1b644ccfec9082eb1e::$classMap;

        }, null, ClassLoader::class);
    }
}

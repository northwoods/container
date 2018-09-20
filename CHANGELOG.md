# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [3.2.0]

### Added

- `InjectorContainer` now implements `container-interop`

## [3.1.1]

### Fixed

- `LazyInjectorBuilder` now creates and applies configs immediately

## [3.1.0]

### Added

- Allow `Zend\ContainerFactory` to use an existing injector instance

## [3.0.0]

### Added

- `Zend\Config` and `Zend\ContainerFactory` for improved Zend Service Manager compatibility

### Removed

- `Config\ServiceConfig` has been replaced with `Zend\Config`

## [2.1.0]

### Added

- Added `LazyInjectorBuilder` to allow configurations to be loaded by class name

## [2.0.0]

### Changed

- Use PHP 7.1+ syntax and features; all classes and interfaces are now strictly typed

## [1.2.1]

### Fixed

- Correct `provide` configuration for Composer

## [1.2.0]

### Added

- `ServiceConfig` now shares instances by default and supports the `shared` and `shared_by_default` options

## [1.1.0]

### Added

- Add `InjectorBuilder` to programmatically configure the injector
- Add `ContainerConfig` to apply container aliasing for injector
- Add `ServiceConfig` to apply service definitions to injector

## [1.0.2]

### Fixed

- All injector references are now considered valid

## [1.0.1]

### Fixed

- Injector aliases are now handled correctly

## [1.0.0]

Initial release.

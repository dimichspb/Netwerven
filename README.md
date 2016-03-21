# Netwerven
## Models repository implementation

### Public methods
```PHP
VacancyRepository::using(DataSource $dataSource)
```
Add using of the specified DataSource by the repository

```PHP
VacancyRepository::all()
```
Returns array of all ModelContainers from all DataSources of the repository

```PHP
VacancyRepository::add(Model $model)
```
Adds specified Model to the repository

```PHP
VacancyRepository::update(Model $model)
```
Updates the Model in the repository

```PHP
VacancyRepositoty::delete(Model $model)
```
Deletes the Model from the repository

### Example implementation
```
php example.php
```

### Testing
```
cd Test
phpunit Tests
```

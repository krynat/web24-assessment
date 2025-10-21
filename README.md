# Company & Employee API

## Installation
Clone this repository using
```
git clone git@github.com:krynat/web24-assessment.git
```

then run init.sh inside a Docker container to create database and run fixtures:
```
docker exec -it web24-assessment_php sh ./docker/php/init.sh
```

---

## API endpoints

### Company

| HTTP Method | Endpoint                  | Description            |
|-------------|---------------------------|------------------------|
| GET         | /api/companies            | Get list of companies  |
| GET         | /api/companies/{id}       | Get single company     |
| POST        | /api/companies            | Create a new company   |
| PUT/PATCH   | /api/companies/{id}       | Update company by ID   |
| DELETE      | /api/companies/{id}       | Delete company by ID   |


### Employee
| HTTP Method | Endpoint                               | Description              |
|-------------|----------------------------------------|--------------------------|
| GET         | /api/companies/{companyId}/employees   | Get list of employees    |
| GET         | /api/employees/{id}                    | Get employee by ID       |
| POST        | /api/companies/{companyId}/employees   | Create a new employee    |
| PUT/PATCH   | /api/employees/{id}                    | Update employee by ID    |
| DELETE      | /api/employees/{id}                    | Delete employee by ID    |

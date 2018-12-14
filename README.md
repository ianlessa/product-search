# Product Search Api

> A Simple Product Search RESTful API with an user interface to test it!

[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/0fa7ed5c857f4c00823c6ff857e46153)](https://www.codacy.com/app/ian.lessa/product-search?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ianlessa/product-search&amp;utm_campaign=Badge_Grade)
[![Build Status](https://travis-ci.com/ianlessa/product-search.svg?branch=master)](https://travis-ci.com/ianlessa/product-search) [![Tested on PHP 7.1 to 7.3](https://img.shields.io/badge/tested%20on-PHP%207.1%20|%207.2%20|%207.3-brightgreen.svg?maxAge=2419200)](https://travis-ci.com/ianlessa/product-search)
[![Maintainability](https://api.codeclimate.com/v1/badges/0c2d6b68b80bf1fafd4b/maintainability)](https://codeclimate.com/github/ianlessa/product-search/maintainability)

***Front Page***

![Front Page Demo](docs/images/frontPageDemo.gif?raw=true "Front Page Screenshot")

---

## Table of Contents

- [Installation](#installatton)
    - [About Database](#about-database)
- [Features](#features)
- [API](#api)
    - [Default Search](#default-search)
    - [Search product By Name](#search-product-by-name)
    - [Filter Search](#filter-search)
    - [Paginated search](#paginated-search)
    - [Sort Search](#sort-search)
    - [Putting all together](#putting-all-together)
- [Tests](#tests)
    - [Integration Tests](#integration-tests)
    - [Unit Tests](#unit-tests)
- [License](#license)

---

## Installation
###### [▲ Table of Contents](#table-of-contents) 

If you don't want to build the Docker image by yourself (by using the [`Dockerfile`](Dockerfile) shipped in this repo), you can just grab it form my [Docker Hub repository](https://hub.docker.com/r/ianlessa/product-search/) of this project, by using the following command:
```shell
$ docker run -d -it -p 80:80 --name desired_container_name ianlessa/product-search
```

This Docker container provides all the services and configuration needed to run the API and its user interface. When you run the `docker run` command you should wait few minutes while all the require services are started. After that you can access the system in your browser.

The project files are located on the `/app` directory of the container.

### About database
###### [▲ Table of Contents](#table-of-contents) 

The database [config](app/config/config.json) file is a JSON file inside the `/app/config/` directory. The default contents are:

```JSON
{
  "DB_HOST" : "localhost",
  "DB_PORT" : "3306",
  "DB_DATABASE" : "product_search",
  "DB_USERNAME" : "root",
  "DB_PASSWORD" : "root"
}
```

If you need, just configure it to your settings. Omitting any of the params described in the JSON file will set it to its the default value.

After the database configuration, you must create the required databases and populate it with product data. The table schema follows the image: 

![Product Table Schema](docs/images/productTable.png?raw=true "Product Table Schema")

All the SQL required to create the table and populate it is shipped with this repo, in the file [`database_up.sql`](database_up.sql).

Now that all the required configurations are set, you are ready to test the system.

## Features
###### [▲ Table of Contents](#table-of-contents) 

This API provides the following features:
- Search Product By Name
- Filter Search By the product attributes:
    - Id
    - Brand
    - Description
- Pagination
- Sorting

To access the Product Search screen, just head to the webpage root directory on your browser. 
You can do a live test of the features on my site [http://ianlessa.com](http://ianlessa.com) as well. There you can see the query sended to the API and the results that it retrieves.

## API
###### [▲ Table of Contents](#table-of-contents) 

Currently, the API have just one endpoint that retrieves the product search results.
Please notice that any misspelled or invalid params will be ignored, and querying with invalid values for parameters will result in a [default search](#default-search).
Combining the params described in this section will result in a `AND` type in `WHERE` section of the SQL query.

#### Default Search 
###### [▲ API](#api) 
`GET /v1/products` - Make a default product search.
 
##### Default Search - Response
```JSON
{
 "search": {
   "filters": [

    ],
    "pagination": {
      "start": 0,
      "perPage": 5
    },
    "sort": null
  },
  "rowCount": 5,
  "maxRows": 17,
  "results": [
    {
      "id": "1",
      "name": "Flowered Dress",
      "brand": "Sunflower",
      "description": "A beautiful flowered dress perfect for a flowered person."
    },
    {
      "id": "2",
      "name": "Daisy Hat",
      "brand": "Sunflower",
      "description": "A cute daisy-shaped hat which is the latest trend in the garden!"
    },
    {
      "id": "3",
      "name": "Banana Pajama",
      "brand": "Banana Boat",
      "description": "A yellow pajama with brown ellipses on it."
    },
    {
      "id": "4",
      "name": "Banana shoes",
      "brand": "Banana Boat",
      "description": "A pair of shoes that makes you look a little bit out of the box."
    },
    {
      "id": "5",
      "name": "Banana Leaf Coat",
      "brand": "Banana Boat",
      "description": "A comfortable coat entirely made of banana leaves."
    }
  ]
}

```

#### Search product By Name 
###### [▲ API](#api) 
`GET /v1/products?q=shoe` - Find a product by its name. A `LIKE` type query will be made.

##### Search product By Name - Response
```JSON
{
  "search": {
    "filters": {
      "name": "shoe"
    },
    "pagination": {
      "start": 0,
      "perPage": 5
    },
    "sort": null
  },
  "rowCount": 3,
  "maxRows": 3,
  "results": [
    {
      "id": "4",
      "name": "Banana shoes",
      "brand": "Banana Boat",
      "description": "A pair of shoes that makes you look a little bit out of the box."
    },
    {
      "id": "7",
      "name": "Beach Shoes",
      "brand": "Tropical Wear",
      "description": "A pair of shoes to use on beach. They are fresh and maintain your feet free of sweat."
    },
    {
      "id": "12",
      "name": "Black Shoes",
      "brand": "Primark",
      "description": "A classic pair of shoes that matches with your lifestyle."
    }
  ]
}
```

#### Filter Search
###### [▲ API](#api) 
`GET /v1/products?filter=brand:primark` - Filter the search by a product attribute. A `LIKE` type query will be made.
Valid attributes are:
- id 
- name
- brand
- description

##### Filter Search - Response
```JSON
{
  "search": {
    "filters": {
      "brand": "primark"
    },
    "pagination": {
      "start": 0,
      "perPage": 5
    },
    "sort": null
  },
  "rowCount": 4,
  "maxRows": 4,
  "results": [
    {
      "id": "10",
      "name": "New Year's Eve Dress",
      "brand": "Primark",
      "description": "A white dress that helps your dreams come true in the next year."
    },
    {
      "id": "11",
      "name": "Black Jeans",
      "brand": "Primark",
      "description": "An all purpose casual pair of jeans."
    },
    {
      "id": "12",
      "name": "Black Shoes",
      "brand": "Primark",
      "description": "A classic pair of shoes that matches with your lifestyle."
    },
    {
      "id": "13",
      "name": "Black Sneakers",
      "brand": "Primark",
      "description": "For youngsters of all ages."
    }
  ]
}
```

#### Paginated search
###### [▲ API](#api) 
`GET /v1/products?per_page=2&start_page=2` - Set the search pagination. 

The valid values for these params are:
- start_page: Positive numbers including 0. Default value is 0.
- per_page: Positive numbers bigger than 0. Default value is 5.

Any invalid value in those params will result in a [default search](#default-search).
Omitting any of the parameters will result in the default values for them.

##### Paginated search - Response
```JSON
{
  "search": {
    "filters": [
      
    ],
    "pagination": {
      "start": 2,
      "perPage": 2
    },
    "sort": null
  },
  "rowCount": 2,
  "maxRows": 17,
  "results": [
    {
      "id": "5",
      "name": "Banana Leaf Coat",
      "brand": "Banana Boat",
      "description": "A comfortable coat entirely made of banana leaves."
    },
    {
      "id": "6",
      "name": "Aviator Sunglasses",
      "brand": "Tropical Wear",
      "description": "If you want to fly away, make sure to have one of these."
    }
  ]
}
```

#### Sort Search
###### [▲ API](#api) 
`GET /v1/products?sort=desc:description` - Sort search by a product attribute.

Valid sort types are `asc` and `desc`, which will sort the results in a ascending and descending order, respectively.

Valid values are the same of an [filter search](#filter-search):
- id 
- name
- brand
- description

##### Sort Search - Response
```JSON
{
  "search": {
    "filters": [
      
    ],
    "pagination": {
      "start": 0,
      "perPage": 5
    },
    "sort": {
      "value": "description",
      "type": "DESC"
    }
  },
  "rowCount": 5,
  "maxRows": 17,
  "results": [
    {
      "id": "9",
      "name": "Clover Glasses",
      "brand": "Sunflower",
      "description": "Put some luck in your life with the brand new product of our eyewear collection!"
    },
    {
      "id": "6",
      "name": "Aviator Sunglasses",
      "brand": "Tropical Wear",
      "description": "If you want to fly away, make sure to have one of these."
    },
    {
      "id": "8",
      "name": "Beach Coat",
      "brand": "Tropical Wear",
      "description": "If you think that bikinis are too revealing, this product is perfect for you!"
    },
    {
      "id": "13",
      "name": "Black Sneakers",
      "brand": "Primark",
      "description": "For youngsters of all ages."
    },
    {
      "id": "11",
      "name": "Black Jeans",
      "brand": "Primark",
      "description": "An all purpose casual pair of jeans."
    }
  ]
}
```

#### Putting all together
###### [▲ API](#api) 
`GET /v1/products?q=coat&filter=brand:primark&sort=desc:id&per_page=1&start_page=1` - Make a complex search.

All the parameters described above can be combined.

##### Putting all together - Response
```JSON
{
  "search": {
    "filters": {
      "name": "coat",
      "brand": "primark"
    },
    "pagination": {
      "start": 1,
      "perPage": 1
    },
    "sort": {
      "value": "id",
      "type": "DESC"
    }
  },
  "rowCount": 1,
  "maxRows": 2,
  "results": [
    {
      "id": "19",
      "name": "Warm Coat",
      "brand": "Primark",
      "description": "Helps you pass through the most intense winter!"
    }
  ]
}
``` 

## Tests
###### [▲ Table of Contents](#table-of-contents) 

This repo have `Unit` and `Integration` tests, made with `PHPUnit`. The tests are under [Test](Test) folder. Each type of test have its on folder, and the directory structure
were made to reflect the actual source code directory structure, to ease the finding of the test and its tested class.
Before you run the tests, please refer to the [Integration Tests](#integration-tests) section. There are some configs that should be made in order to test correctly.

There are two test suites configured. To run both, just type the following command in the project root dir: 
```shell
$ vendor/bin/phpunit
```

You can run the tests inside the [docker container](#installation) of this project as well.

#### Integration Tests
###### [▲ Tests](#Tests) 

Since the integration tests performs operations in the database you should configure the access to it  
in the same way as described on the [About Database](#about-database) section. However, the configurations
must be made at the [phpunit.xml](phpunit.xml) file, located on the project root. The `<php>` section of the xml
follows the same principle of the [config.json](app/config/config.json) file: 

```xml
<php>
    <var name="DB_HOST" value="localhost" />
    <var name="DB_PORT" value="3306" />
    <var name="DB_DATABASE" value="product_search_test" />
    <var name="DB_USERNAME" value="root" />
    <var name="DB_PASSWORD" value="root" />
</php>
```

To populate the product table of the product_search_test database, 
you must use the insert statement of the [database_up.sql](database_up.sql) file, 
as the tests are made based on these data.

##### Running Integration tests

To run the Integration Test suite, just execute this command on project root:

```shell
$ vendor/bin/phpunit --testsuite V1_IntegrationTest
``` 

#### Unit Tests
###### [▲ Tests](#Tests) 

The Unit tests require no further configuration. To run the Unit Test suite, just run the following command:

```shell
$ vendor/bin/phpunit --testsuite V1_UnitTest
``` 

## License
###### [▲ Table of Contents](#table-of-contents) 

[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org)

This Repository is under [MIT license](http://opensource.org/licenses/mit-license.php). Feel free to use an modify this as desired!

# Product Search Api

> A Simple product search RESTful API with an user interface to test it!

[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org)
[![Build Status](https://travis-ci.com/ianlessa/ecf-experiments.svg?branch=master)](https://travis-ci.com/ianlessa/ecf-experiments) [![Tested on PHP 5.6 to nightly](https://img.shields.io/badge/tested%20on-PHP%205.6%20|%207.0%20|%207.1%20|%207.2%20|%20hhvm%20|%20nightly-brightgreen.svg?maxAge=2419200)](https://travis-ci.com/ianlessa/ecf-experiments)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f98c894a10374db886c87ecb653c92bd)](https://www.codacy.com/app/ian.lessa/ecf-experiments?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ianlessa/ecf-experiments&amp;utm_campaign=Badge_Grade)
[![Maintainability](https://api.codeclimate.com/v1/badges/199f4b77170b54cb7e58/maintainability)](https://codeclimate.com/github/ianlessa/ecf-experiments/maintainability) 
[![Test Coverage](https://api.codeclimate.com/v1/badges/199f4b77170b54cb7e58/test_coverage)](https://codeclimate.com/github/ianlessa/ecf-experiments/test_coverage) 

***Front Page***

![Front Page Screenshot](docs/images/FrontPage.png?raw=true "Front Page Screenshot")

---

## Table of Contents

- [Installation](#installatton)
- [Features](#features)
- [API](#api)
    - [Default Search](#default-search)
- [Docker](#docker)
- [Tests](#tests)
- [License](#license)

---

## Installation

This system depends on PHP 7.1, Apache (with mod_rewrite enabled) and MySQL to run. Make sure to install the environment dependencies before proceed to the repository installation.

- Clone this repo to your local machine using `https://github.com/ianlessa/product-search`, inside your preferred dir:

```shell
$ git clone https://github.com/ianlessa/product-search.git
```
- Inside the folder that you have just cloned the repo, you'll need to install the project dependencies:

```shell
$ composer install -vvv
```

Before that, configure your server to point correctly to the directory of the repo. After that, proceed to the database settings.

### Database Settings

The database config file is a JSON file inside the `/app/config/` directory. The default contents are:

```JSON
{
  "DB_HOST" : "localhost",
  "DB_PORT" : "3306",
  "DB_DATABASE" : "product_search",
  "DB_USERNAME" : "root",
  "DB_PASSWORD" : "root"
}
```

Just configure it to your settings. Omitting any of the params described in the JSON file will set it to its the default value.

After the database configuration, you must create the required databases and populate it with product data. The table schema follows the image: 

![Product Table Schema](docs/images/productTable.png?raw=true "Product Table Schema")

All the SQL required to create the table and populate it is shipped with this repo, in the file `database_up.sql`.

Before that, you are ready to test the system.

## Features

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
###### RTL [Head to Table of contents](#table-of-contents) 

Currently, the API have just one endpoint that retrieves the product search results.

##### Default Search 
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
}
```

## Docker

@todo

## Tests

@todo

## License

[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org)

This Repository is under [MIT license](http://opensource.org/licenses/mit-license.php). Feel free to use an modify this as desired!
# Product Search Api

> A Simple Product Search RESTful API with an user interface to test it!

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
    - [Database Settings](#database-settings)
- [Features](#features)
- [API](#api)
    - [Default Search](#default-search)
    - [Search product By Name](#search-product-by-name)
    - [Filter Search](#filter-search)
    - [Paginated search](#paginated-search)
    - [Sort Search](#sort-search)
    - [Putting all together](#putting-all-together)
- [Docker](#docker)
- [Tests](#tests)
- [License](#license)

---

## Installation
###### [▲ Table of Contents](#table-of-contents) 

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

Just configure it to your settings. Omitting any of the params described in the JSON file will set it to its the default value.

After the database configuration, you must create the required databases and populate it with product data. The table schema follows the image: 

![Product Table Schema](docs/images/productTable.png?raw=true "Product Table Schema")

All the SQL required to create the table and populate it is shipped with this repo, in the file [`database_up.sql`](database_up.sql).

Before that, you are ready to test the system.

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
- per_page: Positive numbers bigger than 0.
- start_page: Positive numbers including 0.

Any invalid value in those params will result in a [default search](#default-search)

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
`GET /v1/products?q=coat&filter=brand:primark&sort=desc:id&per_page=1&start_page=1` - All the parameters described above can be combined.

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

## Docker
###### [▲ Table of Contents](#table-of-contents) 

@todo

## Tests
###### [▲ Table of Contents](#table-of-contents) 

@todo

## License
###### [▲ Table of Contents](#table-of-contents) 

[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org)

This Repository is under [MIT license](http://opensource.org/licenses/mit-license.php). Feel free to use an modify this as desired!
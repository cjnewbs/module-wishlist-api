# Usage

### Authentication
All endpoints require customer authentication. Use the endpoint `/V1/integration/customer/token` as described [here](https://magento.redoc.ly/2.4.1-customer/#operation/integrationCustomerTokenServiceV1CreateCustomerAccessTokenPost) to get a customer token.

### Retrieving the customer's wishlist
Endpoint: `GET /V1/wishlist`

Example Response:
```
{
    "wishlist_id": 4,
    "customer_id": 1,
    "is_shared": null,
    "sharing_code": "1m7Tc6K1LZP3hx7ENkR36ehUECoD6b3H",
    "items": [
        {
            "wishlist_item_id": 6,
            "wishlist_id": 4,
            "product_id": 11,
            "store_id": 1,
            "added_at": "2020-11-30 03:53:10",
            "qty": 3,
            "product_name": "Configurable Test",
            "price": 0
        },
        {
            "wishlist_item_id": 7,
            "wishlist_id": 4,
            "product_id": 12,
            "store_id": 1,
            "added_at": "2020-11-30 22:12:08",
            "qty": 3,
            "product_name": "Bundle Test",
            "price": 0
        },
        {
            "wishlist_item_id": 8,
            "wishlist_id": 4,
            "product_id": 12,
            "store_id": 1,
            "added_at": "2020-12-02 04:19:09",
            "qty": 2,
            "product_name": "Bundle Test",
            "price": 0
        },
        {
            "wishlist_item_id": 9,
            "wishlist_id": 4,
            "product_id": 1,
            "store_id": 1,
            "added_at": "2020-12-02 04:57:33",
            "qty": 1,
            "product_name": "Simple Test",
            "price": 99
        }
    ]
}
```

### Add to wishlist
Endpoint: `POST /V1/wishlist`

#### Simple Product:
```
{
    "item": {
        "product": 1,
        "qty": 1
    }
}
```

#### Configuration Data
For Configurable and Bundle products the configuration data is passed to the `custom_attributes` field.
To identify what values are needed to be passed to the API use the regular front-end to select your desired configuration. Open the browser's network inspector and click the "Add to Wishlist" button. The request will look something like this:
```
product: 11
uenc: aHR0cHM6Ly9tYWdlbnRvLnRlc3QvY29uZmlndXJhYmxlLXRlc3QuaHRtbA,,
super_attribute[93]: 7
super_attribute[137]: 5
qty: 1
form_key: h6jYhXWUzAbXZRpT
```

As you can see from the API example below, to translate into a request compatible with the API remove all the `[]` characters and separate using the `_` character. The `uenc` and `form_key` fields are not required.

#### Configurable Product:
```
{
    "item": {
        "product": 11,
        "qty": 1,
        "custom_attributes": {
            "super_attribute_93": 7,
            "super_attribute_137": 5
        }
    }
}
```

#### Bundle Product:
```
{
    "item": {
        "product": 12,
        "qty": 1,
        "custom_attributes": {
            "bundle_option_1": 1,
            "bundle_option_qty_1": 1,
            "bundle_option_3": 7,
            "bundle_option_qty_3": 1,
            "bundle_option_2_4": 4,
            "bundle_option_2_5": 5,
            "bundle_option_4_8": 8,
            "bundle_option_4_10": 10
        }
    }
}
```

### Delete Wishlist Item
Endpoint: `DELETE /V1/wishlist/:itemId`
  - Success returns a `200` response code and a response body of `true`,
  - Possible errors:
    - Attempting to delete an item that is not in your wishlist returns a `404` response code and the response body (In this example the `:itemId` param was `8`):
    
```
{
    "message": "No item with ID %1",
    "code": 1,
    "parameters": [
        8
    ]
}
```

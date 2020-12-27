# Lumen Vending

## Documentation

    The Lumen vending application is built to make setup as easy as possible, using docker and other tools to perform setup with a single command.  This application will handle 3 inventory items and support adding coins, refunding, coins, and purchasing individual items.

## Getting Started

    - Clone this repository
    - Ensure that Docker is installed and running on the system
    - Run the command 'docker-compose up'
    - If given success messages, this application should then be able to be communicated with on localhost:8074

## Troubleshooting

    - This application is set to use port 8074, if this port is already in use, the port can be changed in the root directory docker-compose.yml on line 11

## Endpoints

    Method | Route | Request Body | Response Code | Response Headers | Response Body
    ```
    PUT | / | { “coin”: 1 } | 204 | X-Coins: ${number of coins accepted} |

    DELETE | / | | 204 | X-Coins: ${number of coins returned} |

    GET | /inventory | | 200 | | An array of remaining item quantities (an array of integers)

    PUT | /inventory/:id || 200  | X-Coins: ${number of coins returned} X-Inventory-Remaining: ${item quantity} | { “quantity”: ${number of items purchased} }
    ```

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

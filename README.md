# kontuak

Domain code for the kontuak project.

A home economy manager.

The main target for the development of this project is to try and train concepts as DDD, TDD, Hexagonal architecture, 
REST apis, and technologies as Symfony2, Doctrine, Polymer... and any one that looks interesting to me.
 
Its intended for use along with the repositories:
* https://github.com/AitorGuerrero/kontuak-rest-api as REST API
* https://github.com/AitorGuerrero/kontuak-web-client as Web Client

##Milestones##
1. Movement confirmation. When a movement is a future movement, it could be configured to be confirmed manually. If it is not confirmed, it does'nt count for the current total amount calculation.
2. Basic User entity. The user management will be done at the API level, here it only will have an ID, and will be used to relacionate movements with users ids.
3. Accounts. Movements will be linked to accounts (like different bank accounts, or in hand money).
4. Total calculations for each account.
5. Whislist. Movements that the user wants to have in the future.
6. Whislist movements confirmation. A movement can be created from a whislisted movement.
7. Whislist prediction. Prediction of when the user would be able to make a whislisted movement.

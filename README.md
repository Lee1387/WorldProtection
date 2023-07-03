<h1>WorldProtection</h1>

**NOTICE:** This plugin is for PocketMine-MP 5. <br/>
✨ **Protect your world from grief, pvp, commands, items, and decaying blocks.**

## Features
- [x] Per world settings
- [x] Protect world from break/place
- [x] Per world pvp
- [x] Ban items in world
- [x] Ban commands in world
- [x] Anti farm land decay in world

## Commands
- Default command: `/worldprotection`
- Aliases: `/wp`

| Command          | Description                       |
|------------------|-----------------------------------|
| `/wp`            | Shows help                         |
| `/wp build`      | Locks world, not even Op can use. |
| `/wp pvp`        | Enable/disable pvp in world.      |
| `/wp antidecay`  | Enable/disable decaying blocks in world.    |
| `/wp keepinventory` | Enable/disable keep inventory.    |
| `/wp keepexperience`       | Enable/disable keep experience.   |
| `/wp banitem`    | Ban items in world.                |
| `/wp unbanitem`  | Unban items in world.              |
| `/wp bancmd`     | Ban commands in world.             |
| `/wp unbancmd`   | Unban commands in world.           |

## Permissions
| Permission       | Description                       |
|------------------|-----------------------------------|
| `worldprotection.command` | Allow use of all commands.       |
| `worldprotection.build.bypass` | Allows players to build in locked worlds |

## Documentation
- This plugin allows you to protect your world from grief, pvp, commands, items, and decaying blocks.
- For example, you can use `/wp build world true` to prevent other players from destroying your world named `world`.
- You can also use /wp pvp world false to disable pvp in a world.
- If you want to keep a certain inventory after you die then you can run this command, editing 'NAME' to your world name `/wp keepinv NAME true`. This world will now keep your inventory after you die.
- If you do not know the `TypeId` of a block then don't worry, just hold the item in your hand and run this command, editing 'NAME' to your world name `/wp banitem NAME` 

## TODO
- [ ] World Borders
- [x] Per world gamemodes
- [ ] Limit players in worlds
- [ ] No explosions in worlds


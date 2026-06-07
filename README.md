# Paling

An HTTP API for a picket paling. It divides a panel into pickets and gaps, can add a gravel board under the pickets, and counts the pickets and the wood in the pickets, the rails, and the board.

It is for someone setting out a garden fence at home. The sample panel is made up. The service does not store names, addresses, or customer orders.

Written in PHP 8.3 or newer, with Laravel. Tests use PHPUnit.

## Run

```bash
composer install
php artisan test
php artisan serve --host=127.0.0.1 --port=8026
```

The server is at http://127.0.0.1:8026.

```bash
curl http://127.0.0.1:8026/api/sample
curl -X POST http://127.0.0.1:8026/api/panels -H "Content-Type: application/json" -d "{\"name\":\"Sample garden paling\",\"widthMm\":1420,\"heightMm\":900,\"picketMm\":70,\"gapMm\":20,\"rails\":2}"
```

A panel is a name, a width, and a height. Leave the picket width, the gap, and the rail count out to keep 70 mm pickets, 20 mm gaps, and 2 rails. The pickets and the gaps fill the width, including a gap at each end, and the count must come out as a whole number of pickets. Each picket is the full height when there is no gravel board. Each rail is the full width. Leave `gravelMm` out for no gravel board. A gravel board is from 80 to 300 mm. It sits under the pickets, so the pickets shorten by that height, and the board is one stick the width of the panel. The pickets must stay at least 200 mm. The sample garden paling is 1420 by 900 mm: 16 pickets and 17240 mm of wood. The same panel with a 150 mm gravel board has pickets 750 mm long and 16260 mm of wood, including a 1420 mm board.

## Layout

```
app/Paling/     sizes, the picket run, checks
routes/         the HTTP API
examples/       a fictional panel
tests/          unit tests
```

## Next

Four updates remain. After those, about 38 hours of later work stay in [ROADMAP.md](ROADMAP.md).

## License

MIT

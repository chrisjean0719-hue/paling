# Paling

An HTTP API for a picket paling. It divides a panel into pickets and gaps, and counts the pickets and the wood in the pickets and the rails.

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

A panel is a name, a width, and a height. Leave the picket width, the gap, and the rail count out to keep 70 mm pickets, 20 mm gaps, and 2 rails. The pickets and the gaps fill the width, including a gap at each end, and the count must come out as a whole number of pickets. Each picket is the full height. Each rail is the full width. The sample garden paling is 1420 by 900 mm: 16 pickets and 17240 mm of wood.

## Layout

```
app/Paling/     sizes, the picket run, checks
routes/         the HTTP API
examples/       a fictional panel
tests/          unit tests
```

## Next

Five updates remain. After those, about 38 hours of later work stay in [ROADMAP.md](ROADMAP.md).

## License

MIT

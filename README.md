# Paling

An HTTP API for a picket paling. It divides a panel into pickets and gaps, can add a gravel board under the pickets, can add a point on each picket, can place a post at each end, counts the pickets and the wood, and returns the elevation as an SVG.

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
curl http://127.0.0.1:8026/api/sample/elevation
curl -X POST http://127.0.0.1:8026/api/panels -H "Content-Type: application/json" -d "{\"name\":\"Sample garden paling\",\"widthMm\":1420,\"heightMm\":900,\"picketMm\":70,\"gapMm\":20,\"rails\":2}"
```

A panel is a name, a width, and a height. Leave the picket width, the gap, and the rail count out to keep 70 mm pickets, 20 mm gaps, and 2 rails. The pickets and the gaps fill the width, including a gap at each end, and the count must come out as a whole number of pickets. Each picket is the full height when there is no gravel board. Each rail is the full width. Leave `gravelMm` out for no gravel board. A gravel board is from 80 to 300 mm. It sits under the pickets, so the pickets shorten by that height, and the board is one stick the width of the panel. The pickets must stay at least 200 mm. The sample garden paling is 1420 by 900 mm: 16 pickets and 17240 mm of wood. The same panel with a 150 mm gravel board has pickets 750 mm long and 16260 mm of wood, including a 1420 mm board. Leave `pointMm` out for square tops. A point is from 20 to 80 mm, cut above the straight picket, and that extra wood is one point on every picket. A 40 mm point on the sample adds 640 mm, so the wood becomes 17880 mm. The straight picket length does not change. Leave `setMm` out for no posts. A set is from 300 to 900 mm below the panel, and it places two posts. Each post is the panel height plus that set. The points do not lengthen the posts. A 450 mm set on the sample makes each post 1350 mm, and the wood becomes 19940 mm. `GET /api/sample/elevation` and `POST /api/panels/elevation` return the drawing as an SVG. One millimetre is one unit. The sample file is `sample-garden-paling-elevation.svg`, 1420 by 916 units, with the first picket at x 0, y 16. The second picket starts after the 20 mm gap. The two rails cross the pickets at y 296 and y 596. A point rises above its picket, a gravel board fills the bottom of the panel, and posts stand at each end with the set hanging below.

## Layout

```
app/Paling/     sizes, the picket run, checks
routes/         the HTTP API
examples/       a fictional panel
tests/          unit tests
```

## Next

One update remains. After that, about 38 hours of later work stay in [ROADMAP.md](ROADMAP.md).

## License

MIT

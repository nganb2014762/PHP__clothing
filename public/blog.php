<?php
include_once __DIR__ . '../../partials/boostrap.php';
include_once __DIR__ . '../../partials/header.php';
require_once __DIR__ . '../../partials/connect.php';
?>

<title>Blog</title>
</head>
<!-- blogs -->
<section id="blogs" class="my-5 py-5">
    <div class="container">
        <div class="title text-center py-5 mt-3 pt-5">
            <h2 class="position-relative d-inline-block">An Extraordinary Commitment To Quality</h2>
            <p>No matter what item you choose, you’ll find these things to be true:</p>
            <hr class="mx-auto">
        </div>
        <div class="row g-3">
            <div class="card border-0 col-md-6 col-lg-4 bg-transparent my-3">
                <img src="img/poster/durable.webp" alt="">
                <div class="card-body px-0">
                    <h4 class="card-title">DURABLE. <br> NOT DISPOSABLE.</h4>
                    <p class="card-text mt-3 text-muted">We make products from quality materials that are built to last,
                        in straightforward styles that endure beyond trends.
                        Unlike most stuff you can buy, it's made to be worn—and not end up in a landfill.</p>
                </div>
            </div>

            <div class="card border-0 col-md-6 col-lg-4 bg-transparent my-3">
                <img src="img/poster/assets_7acommunity.webp" alt="">
                <div class="card-body px-0">
                    <h4 class="card-title">COMMUNITY POWERED <br> SUPPLY CHAIN.</h4>
                    <p class="card-text mt-3 text-muted">Everything we make is made possible by a supply chain of people
                        who touch the product,
                        make it better and in turn are supported and strengthened. Through our partnership, they gain
                        skills and opportunity,
                        and their communities are revitalized. It’s a virtuous cycle of good and humanity.</p>
                </div>
            </div>

            <div class="card border-0 col-md-6 col-lg-4 bg-transparent my-3">
                <img src="img/poster/made.webp" alt="">
                <div class="card-body px-0">
                    <h4 class="card-title">MADE RIGHT. <br> RIGHT HERE.</h4>
                    <p class="card-text mt-3 text-muted">We make our products in the USA because keeping things close
                        means we can better control
                        our supply chain—and in turn, our quality, service, and footprint.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end of blogs -->

<?php
include_once __DIR__ . '../../partials/footer.php';
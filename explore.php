<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['tourist']);

require_once 'Classes/Listing.php';

$keyword  = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;
$type     = isset($_GET['type']) ? $_GET['type'] : null;
$city     = isset($_GET['city']) ? $_GET['city'] : null;
$sort     = isset($_GET['sort']) ? $_GET['sort'] : 'latest';

$listings = Listing::searchListings($keyword, $type, $city, $sort);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Explore - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="index.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <a href="explore.php" class="nav-link">Explore</a>
                <a href="mybookings.php" class="nav-link">My Bookings</a>
                <a href="touristmessages.php" class="nav-link">Messages</a>
                <a href="touristnotifications.php" class="nav-link">Notifications</a>
				<a href="touristdashboard.php" class="nav-link">Dashboard</a>
            </td>
            <td class="nav-buttons-cell">
                <span class="admin-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <h2 class="admin-page-title">Explore Sri Lanka</h2>
    <p class="admin-page-subtitle">Find tour guides, hotels, shops and experiences across Sri Lanka</p>

    <!-- SEARCH & FILTER BAR -->
    <div class="explore-search-bar">
        <form name="searchform" method="get" action="explore.php">

            <input type="text" name="keyword" placeholder="Search by name, city or description..."
                   value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>"
                   class="search-input">

            <select name="type" class="search-select">
                <option value="">All Types</option>
                <option value="tour_guide" <?php echo (isset($_GET['type']) && $_GET['type']=='tour_guide') ? 'selected' : ''; ?>>Tour Guide</option>
                <option value="hotel" <?php echo (isset($_GET['type']) && $_GET['type']=='hotel') ? 'selected' : ''; ?>>Hotel</option>
                <option value="shop" <?php echo (isset($_GET['type']) && $_GET['type']=='shop') ? 'selected' : ''; ?>>Shop</option>
                <option value="experience" <?php echo (isset($_GET['type']) && $_GET['type']=='experience') ? 'selected' : ''; ?>>Experience</option>
            </select>

            <select name="city" class="search-select">
                <option value="">All Cities</option>
                <option value="Colombo" <?php echo (isset($_GET['city']) && $_GET['city']=='Colombo') ? 'selected' : ''; ?>>Colombo</option>
                <option value="Galle" <?php echo (isset($_GET['city']) && $_GET['city']=='Galle') ? 'selected' : ''; ?>>Galle</option>
                <option value="Kandy" <?php echo (isset($_GET['city']) && $_GET['city']=='Kandy') ? 'selected' : ''; ?>>Kandy</option>
                <option value="Jaffna" <?php echo (isset($_GET['city']) && $_GET['city']=='Jaffna') ? 'selected' : ''; ?>>Jaffna</option>
                <option value="Negombo" <?php echo (isset($_GET['city']) && $_GET['city']=='Negombo') ? 'selected' : ''; ?>>Negombo</option>
                <option value="Trincomalee" <?php echo (isset($_GET['city']) && $_GET['city']=='Trincomalee') ? 'selected' : ''; ?>>Trincomalee</option>
                <option value="Batticaloa" <?php echo (isset($_GET['city']) && $_GET['city']=='Batticaloa') ? 'selected' : ''; ?>>Batticaloa</option>
                <option value="Anuradhapura" <?php echo (isset($_GET['city']) && $_GET['city']=='Anuradhapura') ? 'selected' : ''; ?>>Anuradhapura</option>
                <option value="Polonnaruwa" <?php echo (isset($_GET['city']) && $_GET['city']=='Polonnaruwa') ? 'selected' : ''; ?>>Polonnaruwa</option>
                <option value="Matara" <?php echo (isset($_GET['city']) && $_GET['city']=='Matara') ? 'selected' : ''; ?>>Matara</option>
                <option value="Kurunegala" <?php echo (isset($_GET['city']) && $_GET['city']=='Kurunegala') ? 'selected' : ''; ?>>Kurunegala</option>
                <option value="Nuwara Eliya" <?php echo (isset($_GET['city']) && $_GET['city']=='Nuwara Eliya') ? 'selected' : ''; ?>>Nuwara Eliya</option>
                <option value="Hambantota" <?php echo (isset($_GET['city']) && $_GET['city']=='Hambantota') ? 'selected' : ''; ?>>Hambantota</option>
            </select>

            <select name="sort" class="search-select">
                <option value="latest" <?php echo (isset($_GET['sort']) && $_GET['sort']=='latest') ? 'selected' : ''; ?>>Latest</option>
                <option value="price_low" <?php echo (isset($_GET['sort']) && $_GET['sort']=='price_low') ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price_high" <?php echo (isset($_GET['sort']) && $_GET['sort']=='price_high') ? 'selected' : ''; ?>>Price: High to Low</option>
                <option value="rating" <?php echo (isset($_GET['sort']) && $_GET['sort']=='rating') ? 'selected' : ''; ?>>Top Rated</option>
            </select>

            <button type="submit" class="btn btn-primary">Search</button>
            <a href="explore.php" class="btn btn-login" style="margin-left:8px;">Clear</a>

        </form>
    </div>

    <!-- FILTER TABS -->
    <div class="filter-bar" style="margin-bottom:25px;">
        <a href="explore.php" class="filter-btn <?php echo !isset($_GET['type']) ? 'active' : ''; ?>">All</a>
        <a href="explore.php?type=tour_guide" class="filter-btn <?php echo (isset($_GET['type']) && $_GET['type']=='tour_guide') ? 'active' : ''; ?>">Tour Guides</a>
        <a href="explore.php?type=hotel" class="filter-btn <?php echo (isset($_GET['type']) && $_GET['type']=='hotel') ? 'active' : ''; ?>">Hotels</a>
        <a href="explore.php?type=shop" class="filter-btn <?php echo (isset($_GET['type']) && $_GET['type']=='shop') ? 'active' : ''; ?>">Shops</a>
        <a href="explore.php?type=experience" class="filter-btn <?php echo (isset($_GET['type']) && $_GET['type']=='experience') ? 'active' : ''; ?>">Experiences</a>
    </div>

    <!-- LISTINGS GRID -->
    <!-- PHP:
        build WHERE clause based on $_GET params:
        - keyword: WHERE l.title LIKE '%keyword%' OR l.description LIKE '%keyword%' OR l.city LIKE '%keyword%'
        - type: AND l.type = ?
        - city: AND l.city = ?
        - sort: ORDER BY created_at DESC / price ASC / price DESC / avg rating DESC

        $sql = "SELECT l.*, u.firstname, u.lastname,
                AVG(r.rating) as avg_rating,
                COUNT(r.id) as review_count,
                (SELECT image_path FROM listing_images WHERE listing_id = l.id LIMIT 1) as thumbnail
                FROM listings l
                JOIN users u ON l.user_id = u.id
                LEFT JOIN reviews r ON l.id = r.listing_id
                WHERE l.status = 'approved'
                [+ filters]
                GROUP BY l.id
                [+ sort]";
        loop through results and echo each card
    -->

    <div class="listings-grid">

    <?php if(empty($listings)) { ?>
        <p class="table-empty" style="padding:40px; text-align:center;">
            No listings found. Try a different search.
        </p>
    <?php } else { ?>
        <?php foreach($listings as $listing) { ?>
        <a href="listingdetail.php?id=<?php echo $listing['id']; ?>" class="listing-card">
            <div class="listing-card-img">
                <?php if($listing['thumbnail']) { ?>
                    <img src="<?php echo $listing['thumbnail']; ?>" alt="<?php echo $listing['title']; ?>">
                <?php } else { ?>
                    <div class="listing-card-img-placeholder">&#127758;</div>
                <?php } ?>
                <span class="listing-type-badge listing-type-<?php echo $listing['type']; ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $listing['type'])); ?>
                </span>
            </div>
            <div class="listing-card-body">
                <h3 class="listing-card-title"><?php echo $listing['title']; ?></h3>
                <p class="listing-card-location">&#128205; <?php echo $listing['city']; ?></p>
                <p class="listing-card-desc">
                    <?php echo substr($listing['description'], 0, 80) . '...'; ?>
                </p>
                <div class="listing-card-footer">
                    <span class="listing-card-price">Rs. <?php echo number_format($listing['price'], 2); ?></span>
                    <span class="listing-card-rating">
                        &#11088; <?php echo $listing['avg_rating'] ? number_format($listing['avg_rating'], 1) : 'New'; ?>
                        (<?php echo $listing['review_count']; ?>)
                    </span>
                </div>
            </div>
        </a>
        <?php } ?>
    <?php } ?>

</div>

</div>

</body>
</html>
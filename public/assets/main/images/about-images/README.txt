ABOUT-US PAGE IMAGES   (public/assets/main/images/about-images/)
-----------------------------------------------------------------
Name a file like the page address (the part before .html). Allowed: .webp .jpg .jpeg .png .avif

PAGES
  about-sasa-packaging.jpg      main photo of the About page (hero)        (or about-us.jpg - use the page's real address)
  about-sasa-packaging-2.jpg    2nd, 3rd ... photos: the first 3 fill the hero, the rest appear in "A closer look"
  ceo-message.jpg               CEO portrait  (portrait / 4:5 photo works best)
  our-mission.jpg  our-vision.jpg  core-values.jpg  our-strategy.jpg   one photo per page
  (same rule for any other About / service page: {page-address}.jpg and {page-address}-2.jpg ...)

CERTIFICATES
  certificate-iso-9001.png        the seal / logo that sits inside the shield   -> name: "ISO 9001"
  certificate-iso-9001-doc.jpg    OPTIONAL: the full certificate (opens big on "View certificate")
  certificate-fssc.png            -> "FSSC"
  Give a certificate a proper name, issuer, year and text in config/about.php (then: php artisan config:clear)

If a file is missing the page uses the picture uploaded in Admin, and if there is none, a soft sage/blush tile with an icon.

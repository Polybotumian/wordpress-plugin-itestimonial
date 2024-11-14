# Simple Testimonials Plugin for WordPress

## Features
- Admin interface to add, edit, and delete testimonials.
- Securely stores testimonial data in the WordPress database.
- Displays testimonials with interactive transitions using [Slick](https://kenwheeler.github.io/slick/).
- Integrates with the WordPress media library for testimonial photos; if no image is provided, a default profile image is used.

## Installation
1. Place the plugin folder in **/html/wp-content/plugins/**.
2. Activate the "iTestimonials" plugin from the Plugins page.
3. A new admin menu, "iTestimonials," should appear in your dashboard.

## Usage
Navigate to the "iTestimonials" admin menu for an overview or follow the instructions below.

## Shortcode & Parameters
Use `[itestimonials {parameters}]` to embed testimonials with customizable settings.

### Parameters
- **limit** (number): Limits the number of testimonials displayed. Default is `-1` (no limit).
- **selection** (array of IDs): Display specific testimonials by their IDs. *(Note: Currently, IDs can be found only in the database interface as they aren’t shown in the admin menu.)*
- **view** (string): Display format for testimonials. Options are:
  - `"slider"`: A single-row carousel view.
  - `"grid"`: A multi-row grid view where row count can be customized.
- **slidestoshow** (number): Number of slides visible at once (only in `"slider"` view).
- **slidestoscroll** (number): Number of slides to advance per scroll (only in `"slider"` view).
- **rows** (number): Number of rows to display (only in `"grid"` view).
- **slidesperrow** (number): Number of slides per row (only in `"grid"` view).
- **dots** (boolean): Show dot indicators for slide navigation (`true`/`false`).
- **arrows** (boolean): Show previous/next arrows for slide navigation (`true`/`false`).

This setup allows you to easily customize how testimonials are displayed, providing flexibility for different layouts.

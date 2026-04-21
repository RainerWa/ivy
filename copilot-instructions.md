# Copilot Instructions

## Project Overview
This project is a WordPress website for a restaurant.

The website includes:
- a public-facing restaurant website
- a WooCommerce shop
- catering products that customers can order online

## Tech Stack
- WordPress
- WooCommerce
- Parent theme: `storefront`
- Child theme: `ivy`

## Core Rules
- Never modify the parent theme `storefront` directly
- Never modify WordPress core files
- Never modify WooCommerce core/plugin files
- WooCommerce customizations must be update-safe
- Multilingual support is not planned for now
- Prefer lightweight, maintainable solutions aligned with WordPress and WooCommerce best practices

## Theme and Code Structure
All custom project code should be placed in the child theme.

Preferred structure:
- `wp-content/themes/ivy/functions.php`
- `wp-content/themes/ivy/inc/theme-setup.php`
- `wp-content/themes/ivy/inc/template-functions.php`
- `wp-content/themes/ivy/inc/woocommerce.php`
- `wp-content/themes/ivy/inc/products.php`
- `wp-content/themes/ivy/inc/checkout.php`

Rules:
- Organize custom code into topic-based files inside `themes/ivy/inc/`
- Use `functions.php` mainly to load files from `inc/`
- Do not place large amounts of custom logic directly in `functions.php`

## Preferred Implementation Approach
When suggesting or generating code:
1. Prefer existing WordPress and WooCommerce hooks and filters
2. Place code in the child theme under `ivy/inc/`
3. Use template overrides only if hooks and filters are not sufficient
4. Keep WooCommerce customizations update-safe
5. Always mention the target file path for generated code

## WordPress Guidelines
- Follow WordPress best practices
- Escape output correctly
- Sanitize and validate input
- Use translation functions for user-facing strings, even if multilingual support is not currently planned
- Prefer small, focused, readable functions
- Avoid unnecessary dependencies
- Do not edit core files

## WooCommerce Guidelines
- WooCommerce is the main shop plugin
- The shop is primarily used for catering orders
- Prefer product meta, hooks, and filters over template hacks
- Implement product, cart, checkout, and order logic using update-safe WooCommerce APIs
- Keep template overrides to a minimum
- When reasonable, consider compatibility with both simple and variable products
- Do not modify WooCommerce plugin files directly

## Theme Guidelines
- Storefront is the base theme
- All customizations should live in the `ivy` child theme
- Keep styling consistent with the Storefront foundation
- Build responsive and reasonably accessible frontend code
- Avoid unnecessary or overly complex JavaScript

## File Organization Rules
When adding new functionality:
- use an existing topic-based file in `ivy/inc/` when appropriate
- create a new topic-based file if no suitable file exists
- keep `functions.php` focused on loading these files

Examples:
- product-related WooCommerce logic → `inc/products.php`
- cart or checkout logic → `inc/checkout.php`
- generic WooCommerce hooks → `inc/woocommerce.php`
- theme setup logic → `inc/theme-setup.php`
- general template/helper functions → `inc/template-functions.php`

## What to Avoid
- direct edits in `storefront`
- direct edits in WooCommerce core/plugin files
- direct edits in WordPress core
- large custom code blocks inside `functions.php`
- fragile solutions tightly coupled to parent theme markup unless unavoidable
- unnecessary plugins for small features that can be implemented cleanly in code
- quick fixes that reduce maintainability

## Expectations for Generated Code
When generating code:
- always state which file the code should go into
- prefer the smallest reasonable change
- prefer maintainable and update-safe WordPress/WooCommerce patterns
- add comments only where the logic is not obvious
- respect the existing child theme structure
- favor hooks and filters over hard-coded template changes

## Expectations for Responses
When suggesting implementation changes:
- include the target file path
- keep solutions practical and minimal
- consider WooCommerce compatibility
- prioritize update safety
- respect the child theme architecture

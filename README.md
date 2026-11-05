# Glendon ITS SPARK Child Makeover

A separate copy of `glendonits-spark-child`, named
`glendonits-spark-child-makeover`, inheriting from
`glendonits-spark-20151125`. WordPress will use the parent's templates and
functions unless an individual file is added here to override it. This
preserves the existing SPARK page flow, sidebars, ACF fields, menus, and York
header while providing a safe place for incremental redesign.

The initial makeover baseline preserves the copied homepage, existing SPARK
logo, and all three learning pillars. Future homepage changes belong in this
theme; the source child theme remains independent.

## Makeover homepage editing

- Edit the introduction in **Pages → Landing Screen (Home Page)** using the
  main content editor. Its heading and paragraphs appear below the SPARK logo.
- Edit the French introduction using the page's existing **French Content**
  ACF field.
- The three pillars and their module links use the existing **homepage** and
  **homepage-fr** menu locations. All module links are visible without opening
  a category.
- To customize a pillar description, enable **Description** under **Screen
  Options** in Appearance → Menus and edit its top-level menu item. Blank
  descriptions use the theme's short defaults.
- The English legacy label “Pulling It Together” is presented as “Putting It
  Together” on this homepage. Menu URLs and module destinations are preserved.
- Migrated pages share the compact module header: York logo, Popular Links
  and search, without the black York navigation bar.

## Activation checklist

1. Keep `glendonits-spark-20151125` installed and activate **Glendon ITS SPARK Child Makeover** when ready to preview the makeover.
2. Verify the SPARK homepage and representative module pages in English and
   French.
3. Check menu assignments under **Appearance → Menus → Manage Locations**;
   WordPress stores menu-location assignments per theme.
4. Confirm ACF fields, resources, and optional page elements still render.
5. The homepage and first module-page template are in-progress migrations;
   verify their menus and layouts before using them on production.
6. Add future child-specific CSS and templates here rather than editing the
   parent.

## Bootstrap

The parent currently ships Bootstrap 3.3.0 assets and jQuery-dependent
integrations. The child uses pinned, local Bootstrap 5.3.8 assets on the `Home
page` template, the site map, and module pages identified by the `spark_topic`
page field. Those pages use a child-owned York-style header and footer. Module
pages render their section menu, page content, resources, progress, and page
navigation in a responsive Bootstrap 5 layout. The site map preserves its
CMS-managed links and arranges topics in responsive cards. Other pages without
`spark_topic` still use the parent's existing rendering. Bootstrap 3 styles
and Bootstrap-specific integrations are dequeued on migrated pages. See
[BOOTSTRAP5-MIGRATION.md](./BOOTSTRAP5-MIGRATION.md) for the migration
requirements and acceptance checklist. Do not load Bootstrap 3 and 5 globally
together.

The module menu opens the branch containing the current page and closes other
branches. Separate keyboard-accessible disclosure buttons let visitors open
other sections without navigating away.

The numeric `spark_topic` page field chooses the module menu. Values from 10
through 19 select Getting Started; 20–29 select Exploring; 30–39 select
Putting It Together; and 40 or greater select Faculty. For example, the
assignment-requirements page has `spark_topic` set to `11`. This is existing
page metadata; the template does not create or change the field.

## Scope

The homepage, site map, and `spark_topic` module pages have child-theme
templates; other inherited SPARK pages still use the parent's existing layout
and assets until they are specifically migrated in the child.

# Survey Deck Slide 1 Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Redesign Survey pre-demo deck Slide 1 (only) with stronger visual hierarchy, iconography, accent color, and subtle one-time motion on activation, consistent with existing global design.

**Architecture:** Update only the Slide 1 markup in `templates/survey/deck.php` and add Slide 1–scoped CSS in `assets/shared/assets/survey.css`. Keep existing JS behavior (title niche substitution) by preserving the `deckSlide1Title` id.

**Tech Stack:** WordPress PHP templates, existing Lucide SVG assets, vanilla CSS animations, existing survey JS.

---

### Task 1: Redesign Slide 1 markup (two-column + flow cards)

**Files:**
- Modify: `templates/survey/deck.php`

- [ ] **Step 1: Update Slide 1 markup**
  - Add a `deck-slide--intro` class to Slide 1 article.
  - Wrap content in a `deck-intro` grid with `deck-intro-copy` (left) and `deck-intro-flow` (right).
  - Keep `h2#deckSlide1Title` exactly (required by `assets/js/pages/survey.js`).
  - Replace the plain `.deck-list` bullets with 3 “flow cards” that include:
    - Lucide icons (URLs generated via `jcp_core_icon(...)`)
    - Title (existing bullet text)
    - Supporting line (new short sentence)
  - Add a small “signal pill” below lead (copy only; no tracking changes).

- [ ] **Step 2: Manual verify markup**
  - Confirm Slide 1 still renders in the deck and the title updates with niche choice.

- [ ] **Step 3: Commit**
  - Commit message: `feat(survey): redesign pre-demo deck slide 1`

---

### Task 2: Add Slide 1 CSS (accent wash + connector sweep + cards)

**Files:**
- Modify: `assets/shared/assets/survey.css`

- [ ] **Step 1: Add Slide 1–scoped styles**
  - Create `.deck-slide--intro` scoped rules:
    - 2-column grid (stacks on mobile)
    - Accent background wash behind flow area
    - Flow cards with icon badge + depth
  - Add a connector line behind cards with an animated “sweep” that runs once on `.is-active`.

- [ ] **Step 2: Add reduced-motion support**
  - Under `@media (prefers-reduced-motion: reduce)` disable the sweep and any transforms.

- [ ] **Step 3: Manual verify CSS**
  - Check desktop + mobile widths (basic responsive).
  - Ensure existing deck buttons (Back/Next/Skip) are unaffected.

- [ ] **Step 4: Commit**
  - Commit message: `style(survey): add intro slide flow styling + motion`

---

### Task 3: Sanity checks

**Files:**
- Verify: `assets/js/pages/survey.js` (no edits expected)

- [ ] **Step 1: Confirm no PHP syntax errors**
  - Run: `php -l templates/survey/deck.php`
  - Expected: `No syntax errors detected ...`

- [ ] **Step 2: Confirm clean git status**
  - Run: `git status`
  - Expected: working tree clean after commits


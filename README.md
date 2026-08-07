# Leyla Safari Tours

**Premium Tours & Travel Agency Platform Design**

[leylasafaritours.com](https://leylasafaritours.com)

---

## Overview

This document is the comprehensive product, UX, and system design for a premium East African safari platform tailored to **Leyla Safari Tours** — Kenya-focused with Uganda, Tanzania, Rwanda, and custom destinations.

It draws directly from the client questionnaire:

- Earthy / bronze safari colors
- Professional + friendly + adventurous + conservation vibe
- Flexible grouping of packages
- Strong itinerary + price focus
- Request-a-quote first (not instant booking at launch)
- Form + WhatsApp / email inquiries
- Bank / card payments later
- Help with itinerary research and pricing
- Admin content control
- Social proof integration

It also reflects patterns used by leading operators (Go2Africa, Safari.com, Frame The Wild) and modern tour platforms with day-by-day itinerary builders, filters, and conversion-focused layouts.

The platform is designed as a **modern web application** (responsive, mobile-first) with a public-facing website, customer account area, and powerful admin CMS / dashboard. Start lean (request-a-quote + manual handling) and scale to live availability, online payments, and deeper automation.

---

## Current Repository Status

| Layer | Status |
|-------|--------|
| MVP landing page (static) | Implemented |
| Package detail pages | Planned |
| Destinations hub | Planned |
| Admin CMS / dashboard | Planned |
| Customer accounts | Planned |
| Online payments | Planned |
| Live availability | Planned |

The landing page is **informational only** — no backend logic, no CMS, no live booking. It previews brand, trust signals, itinerary transparency, and enquiry UX ahead of full platform build-out.

**Local preview:** `http://localhost/leysafaris/` (XAMPP)

```
leysafaris/
├── index.html          # MVP landing page
├── css/style.css       # Design system + components
├── js/main.js          # Nav, accordions, form validation
├── images/             # Safari photography assets
└── README.md
```

---

## 1. Brand & Visual Design System

### Vibe

Professional yet warm and approachable, adventurous, conservation-minded. Feels curated and trustworthy — not generic or overly corporate. Sells the dream of authentic East African wilderness while remaining easy to navigate and convert.

### Color Palette (earthy + bronze safari)

| Token | Value | Role |
|-------|-------|------|
| Primary | `#2F4F3E` (deep olive / forest green) | Trust, nature, conservation |
| Accent / Bronze | `#B87333` / `#C47A2B` | Luxury safari feel, CTAs |
| Secondary | Savannah ochre, warm terracotta | Highlights, warmth |
| Neutrals | `#F5EDE3` cream / bleached linen, soft beige, charcoal text | Backgrounds, readability |
| Supporting | Soft sage, muted gold, dark acacia | Depth, borders, accents |

Avoid pure black / white extremes; keep everything warm and photographic.

### Typography

- **Headings:** Elegant serif or refined display (e.g. Cinzel, Fraunces) for a premium “Out of Africa” editorial feel
- **Body / UI:** Clean, highly readable sans-serif (Inter, DM Sans, or system stack)
- Hierarchy emphasizes legibility on mobile and under varying light conditions

### Imagery & Motion

- Full-bleed, high-quality authentic photos / videos of wildlife, landscapes, camps, cultural moments, and guests (client has some; source professional licensed or commissioned for gaps)
- Hero sections with subtle video backgrounds or cinematic stills + gentle parallax
- Lazy-loaded galleries with lightbox, captions, and alt text optimized for SEO (e.g. “Lion sighting Masai Mara Leyla Safari”)
- Micro-interactions: soft hover states on cards, smooth accordion opens for itineraries, map pin animations
- Conservation badges and subtle “Positive Impact” or community support messaging where authentic

### Layout Principles

- Mobile-first, fast-loading (<3s target)
- Generous whitespace, large imagery, clear visual hierarchy
- Sticky elements: main CTA (“Request Quote” / “Enquire”), WhatsApp floating button, key filters on listing pages
- Accessibility: high contrast, keyboard navigation, proper ARIA, readable font sizes

---

## 2. Information Architecture & Key User Flows

### Primary Navigation

Clean, short:

- Home
- Destinations (Kenya parks + Uganda, Tanzania, Rwanda + Custom)
- Safaris / Packages (filterable)
- Experiences / Activities
- About / Our Story (conservation, team, why Leyla)
- Journal / Blog (SEO + inspiration)
- Contact / Plan Your Trip
- Account (for logged-in customers, phased)

### Secondary / Utility

- Search
- Currency selector (KES / USD / EUR, later)
- Language (English primary; expand later)

### Core Customer Journeys

1. **Discovery → Inspiration → Detail → Enquiry**
2. **Browse by filters → Package detail (rich itinerary) → Request Quote / Customize**
3. **Saved packages / Wishlist → Account dashboard**
4. **Post-enquiry:** Quote review → (future) Book & Pay → Trip portal with digital itinerary, documents, support
5. **Admin:** Content creation → Publishing → Inquiry management → Reporting

**Primary conversion (early stage):** Request a Quote — matches client preference. Layer real-time availability and online booking later.

---

## 3. Public Website — Key Pages & Features

### Homepage

- Cinematic hero (video or strong still) + short elevator-pitch headline + primary CTA “Plan Your Safari” / “Request a Quote”
- Trust bar: “Kenya · Uganda · Tanzania · Rwanda | Custom Journeys”, ratings, “Conservation-minded”
- Quick destination tiles or “Where to go” (Masai Mara, Amboseli, etc.)
- Featured packages (cards with duration, starting price, key highlight, “View Itinerary”)
- “Why Leyla” short story + conservation angle
- Testimonials / social proof carousel
- Instagram / TikTok feed or highlights (future)
- Final strong CTA + newsletter / “Join the waitlist for annual events”

### Destinations Hub

- Map-first or grid of parks / regions with short evocative descriptions, best time to visit, signature wildlife, and linked packages
- Individual destination pages with deeper content, photo galleries, and related itineraries

### Safaris / Packages Listing

Flexible multi-factor filtering and sorting:

| Filter | Options |
|--------|---------|
| Destination / Park | Mara, Amboseli, Samburu, Serengeti, Bwindi, etc. |
| Duration | Days |
| Budget / Price range | Tiered ranges |
| Experience type | Wildlife, Cultural, Adventure, Beach & Bush, Honeymoon, Family, Group, Luxury, Conservation-focused |
| Traveler type | Solo, Couple, Family, Corporate, Private |
| Departure style | Fixed departure / Private / Custom |
| Specials | Annual scheduled events |

**Card design:** strong photo, title, duration, starting price (or “From $X”), short teaser, key inclusions icons, “View full itinerary” CTA.

Optional later: map view toggle. Sort by popularity, price, duration, newest.

### Package / Tour Detail Page

*Most important conversion page.*

**Above the fold:**

- Hero image / gallery
- Title + short tagline
- Duration, destinations summary, starting price (clear, per person or as noted)
- Availability indicator or “Request dates”
- Primary CTA: “Request a Quote” / “Customize this Safari” (sticky on mobile)
- Secondary: Share, Save, Download PDF itinerary (future)

**Below the fold:**

- **Highlights** (bullet or icon list)
- **Day-by-day Itinerary** (see Section 4)
- Interactive or static route map
- **Inclusions / Exclusions** — transport, accommodation, meals, park fees, guide included; flights & visas excluded but assistance offered
- Accommodation overview (with photos / links if available)
- Optional activities / add-ons
- Pricing notes / seasonal variations / group discounts
- Practical info (best time, fitness level, packing tips, visa notes)
- Reviews / testimonials specific to this or similar trips
- Related packages
- FAQ accordion
- Final sticky or repeated CTA + WhatsApp option

### Experiences / Activities

Standalone or filterable short experiences (game drives, cultural visits, ballooning, gorilla trekking, etc.) that can be added to packages or sold separately later.

### About / Story

Founder story, team, conservation commitments, “why choose us over the big companies” — personal, flexible, authentic, starting slow and building toward luxury.

### Contact / Plan Your Trip

- Clean professional form with fields: name, email, phone / WhatsApp, preferred destinations, travel dates (flexible), group size / composition, budget range, special interests, message
- Instant confirmation + email / WhatsApp notification to admin
- Alternative contact methods clearly displayed
- Optional live chat widget (secondary to form + WhatsApp)

### Customer Account Area (phased)

- Saved packages / wishlist
- Enquiry history + quote status
- (Later) Booked trips with digital itinerary, documents, payments, support chat
- Profile & preferences

---

## 4. Itinerary Implementation (Customer View + Admin Upload)

*Core strength of the platform.*

### Customer-Facing Display

- Vertical timeline or numbered day accordion (expandable for longer trips; all open or summary + expand for shorter)
- Each day card / section contains:
  - Day number + title (e.g. “Day 3 – Full day game drives in Masai Mara”)
  - Location(s) + map pin
  - Morning / Afternoon / Evening breakdown or narrative description
  - Key activities with icons
  - Meals included
  - Accommodation (name + short note + photo if available)
  - Optional activities
  - Approximate travel times / driving notes
  - Wildlife highlights or “What to expect”
- Visual route map that updates or shows the full path (static image initially; interactive later)
- Downloadable branded PDF itinerary (generated from the same data)
- Shareable link (for groups or decision-makers)
- “Customize this day” or overall “Request changes” button that pre-fills the enquiry form

### Admin Itinerary Builder

Powerful but usable:

- Drag-and-drop or sequential day builder
- Reusable day templates / blocks (e.g. “Standard Masai Mara game drive day”, “Amboseli cultural visit”)
- **Per-day fields:** title, rich-text description, locations (with geo), activities (multi-select or free), meals, accommodation selection (from managed list), images, notes, optional add-ons, estimated costs (internal)
- **Overall package fields:** title, slug, short description, long description, destinations, duration (auto-calculated), categories / tags (for filters), pricing tiers or base price + notes, inclusions / exclusions, hero media, gallery, SEO fields, status (draft / published), featured flag
- Ability to duplicate an existing package and modify (very useful for variations)
- Preview mode that shows exactly what the customer will see
- Bulk media upload and organization
- Versioning or “last updated” notes
- Pricing helper tools (admin can attach research notes or cost breakdowns that stay internal)
- Support for scheduled annual events / fixed departures as special packages

Data model supports both fixed packages and highly customizable ones. Admin can mark packages as **“Template – for customization only”**.

---

## 5. Admin Dashboard / CMS

Clean, modern back-office with role-based access: **Super Admin**, **Content Editor**, **Sales / Operations**.

### Key Modules

| Module | Features |
|--------|----------|
| **Dashboard** | Overview of enquiries, recent quotes, popular packages, upcoming departures, quick stats |
| **Packages / Tours** | Full CRUD with itinerary builder; media library; categories; destinations management |
| **Enquiries / Leads** | Inbox-style with status workflow: New → Contacted → Quote Sent → Negotiation → Confirmed / Lost. Notes, assignment, linked package, communication history. Auto-notify via email + WhatsApp |
| **Quotes** | Generate professional branded quotes from package data + customizations. PDF + online view. Track open / acceptance |
| **Content** | Blog / Journal, static pages, testimonials (approve / manage), destinations, experiences |
| **Media Library** | Organized by destination / package, with optimization tools |
| **Customers / CRM light** | Contact records, enquiry history, preferences |
| **Bookings** (later) | Calendar, capacity, payments, passenger lists, vouchers |
| **Settings** | Branding, payment gateways (bank transfer instructions first; later M-Pesa / card via Pesapal / DPO / Stripe), email templates, WhatsApp Business API, users & roles, SEO defaults, currencies |
| **Reports** | Enquiry conversion, popular destinations / packages, traffic sources (basic) |
| **Integrations** | Google Analytics / Tag Manager, WhatsApp, email (SendGrid / Mailgun), social, future OTAs if desired |

---

## 6. Technical & Operational Recommendations

### Stack (flexible)

- Modern headless or traditional: **Next.js / Nuxt + Node** or **Laravel**
- Rapid start: **WordPress** with custom post types + Advanced Custom Fields
- Dedicated tour platforms that can be white-labeled and extended

### Non-Negotiables

- Strong SEO foundation from day one (structured data for tours, fast Core Web Vitals, image optimization, destination landing pages)
- Multi-currency display with clear “prices in USD / pay in KES” notes initially
- WhatsApp deep integration (click-to-chat with pre-filled package context)
- Form → CRM / email automation + internal notifications
- Security, GDPR / CCPA-friendly data handling, SSL, regular backups
- Performance: CDN, image optimization (WebP / AVIF), lazy loading
- Analytics & conversion tracking on every key CTA

### Phased Roadmap

| Phase | Deliverables |
|-------|--------------|
| **1 — MVP** | Beautiful site + package pages with rich itineraries + enquiry form + basic admin content management + WhatsApp |
| **2 — Growth** | Customer accounts, quote generation, better CRM, annual events calendar, more content |
| **3 — Scale** | Live availability calendar, online payments, advanced customization tools, multi-language, deeper integrations, mobile app if needed |

---

## 7. Additional Features (Client-Aligned)

- **Annual scheduled events** that customers can “save up for” — clear calendar + early-bird messaging
- **Strong social proof** section and easy way for admin to add testimonials (including from friends / family initially)
- **Itinerary research help:** admin tools include internal notes and cost worksheets
- **Conservation storytelling** woven throughout (not heavy-handed)
- **Flexible packaging** so almost everything can be customized
- **Professional form-first enquiry** that still feels personal via WhatsApp follow-up
- **Payments:** bank transfer instructions at launch; M-Pesa / card integration later

---

## Trust-First Framework (Landing & Public Site)

These trust signals should be visible across the public site:

| Signal | Implementation |
|--------|----------------|
| Contact information | Persistent header or footer: Nairobi office address, verifiable phone, direct WhatsApp link |
| Email | info@leylasafaritours.com · inquiry@leylasafaritours.com |
| Social proof | Live Google Maps and TripAdvisor review embeds (not static text testimonials) |
| Security | HTTPS enforced; secure payment icons displayed |
| Transparency | Clear day-by-day itineraries; inclusions / exclusions spelled out before enquiry |

---

## Contact (Placeholder)

| | |
|---|---|
| **Domain** | leylasafaritours.com |
| **Email** | info@leylasafaritours.com · inquiry@leylasafaritours.com |
| **Phone / WhatsApp** | +254 712 345 678 |
| **Office** | Ring Road Parklands, Westlands, Nairobi, Kenya |

---

## Design Priority

This design prioritizes **visual storytelling + clear, detailed itineraries + low-friction enquiry** — exactly what converts high-intent safari travelers while matching Leyla’s early-stage operational reality and long-term premium ambitions.

It is flexible enough to grow from a “request a quote” startup into a full booking platform without a complete rebuild.

### Possible Next Expansions

- Wireframe descriptions
- Data models
- Specific component specs
- Content templates
- Prioritized MVP backlog
- Sample copy / structure for key pages

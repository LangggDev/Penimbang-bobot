# DESIGN.md — Sistem Penilaian Bobot Ketidaklayakan Kertas Bekas

> **Version:** 1.0.0
> **Project:** Sistem Penilaian Bobot Ketidaklayakan Kertas Bekas
> **Framework:** Laravel + Blade + Tailwind CSS
> **Database:** MySQL
> **Last Updated:** 2026-06-15
> **Status:** Authoritative design guide for UI/UX implementation
> **Audience:** AI Coding Agents, developer, UI reviewer
> **Principle:** This file describes what the interface should look and feel like. Implementation must not change backend logic.

---

## 0. Project Design Brief

| Field            | Value                                                                                              |
| ---------------- | -------------------------------------------------------------------------------------------------- |
| Product          | Sistem Penilaian Bobot Ketidaklayakan Kertas Bekas                                                 |
| Purpose          | Membantu proses transaksi penimbangan, QC, fuzzy, pembayaran, kasbon, laporan, dan cetak transaksi |
| Main Users       | Penimbang, Quality Control, Kasir                                                                  |
| Environment      | Operasional gudang, proses transaksi harian, input data cepat                                      |
| Design Tone      | Clean, operational, professional, easy to read                                                     |
| UI Priority      | Kejelasan data, kecepatan input, status transaksi mudah dipahami                                   |
| Visual Direction | Light-first interface with dark mode support                                                       |
| Main Constraint  | UI changes must never alter business logic, routes, form names, or database structure              |

The interface must feel like an operational business system, not a decorative landing page. Every page should help staff complete their task quickly and accurately.

---

## 1. User Roles and UX Goals

### 1.1 Penimbang

Penimbang handles the beginning of the transaction flow.

Primary tasks:

* View weighing dashboard.
* Manage customer selection.
* Input first weighing data.
* Perform staged weighing for multiple paper types.
* Complete weighing.
* Print queue number.

Design priorities:

* Weight numbers must be large and easy to read.
* Forms must be clear and fast to complete.
* Transaction status must be visible.
* Staged weighing must show previous weight, current remaining weight, and calculated net weight clearly.
* Buttons for "Simpan", "Selesai Penimbangan", and "Print" must be easy to find.

---

### 1.2 Quality Control

Quality Control evaluates paper quality and reviews fuzzy results.

Primary tasks:

* View items waiting for QC.
* Input paper quality score.
* Save QC assessment.
* Review fuzzy result.
* View fuzzification, inference, and defuzzification details.
* Edit QC history when needed.

Design priorities:

* Paper quality input must be simple and clear.
* Fuzzy result must be visually separated from raw transaction data.
* Fuzzification, inference, and defuzzification should be shown in separate sections.
* Only active rules should be emphasized.
* QC should not see unnecessary cashier/payment actions.

---

### 1.3 Kasir

Kasir handles payment, kasbon deduction, receipt printing, and reports.

Primary tasks:

* View transactions ready for payment.
* Input price per kilogram.
* Apply kasbon deduction.
* Save payment.
* Print receipt.
* View reports and payment details.

Design priorities:

* Currency values must be aligned and readable.
* Total payment must be visually prominent.
* Kasbon deduction must be easy to understand.
* Buttons for payment and print must be clear.
* Report pages must be readable and printable.

---

## 2. Core Design Principles

1. **Clarity first**
   Every page must make the current task obvious.

2. **Operational speed**
   Staff should be able to input data quickly without unnecessary visual distractions.

3. **Consistent status language**
   Status badges must use consistent labels and colors across all roles.

4. **Readable numbers**
   Weight, price, subtotal, debt, and payment totals must use clear formatting.

5. **No unnecessary decoration**
   Avoid excessive gradients, animations, or visual noise.

6. **Print-friendly output**
   Print pages must be simple, black-on-white, and free from navigation elements.

7. **AI safety**
   AI agents may improve UI but must not modify backend logic, route names, database schema, or form field names.

---

## 3. Design Tokens

### 3.1 Color Palette

Use a light-first interface with dark mode support.

#### Brand Colors

| Token                 |     Value | Usage                   |
| --------------------- | --------: | ----------------------- |
| `brand-primary`       | `#0F766E` | Main action color       |
| `brand-primary-hover` | `#115E59` | Primary hover           |
| `brand-soft`          | `#CCFBF1` | Soft teal background    |
| `brand-muted`         | `#5EEAD4` | Accent line, focus ring |

#### Neutral Surfaces

| Token            |     Value | Usage                      |
| ---------------- | --------: | -------------------------- |
| `surface-base`   | `#F8FAFC` | Page background            |
| `surface-card`   | `#FFFFFF` | Card/table/form background |
| `surface-muted`  | `#F1F5F9` | Secondary panel background |
| `surface-subtle` | `#F8FAFC` | Input background           |

#### Dark Mode Surfaces

| Token                 |     Value | Usage                       |
| --------------------- | --------: | --------------------------- |
| `dark-surface-base`   | `#09090B` | Dark page background        |
| `dark-surface-card`   | `#18181B` | Dark card background        |
| `dark-surface-muted`  | `#27272A` | Dark muted section          |
| `dark-surface-subtle` | `#1F2937` | Dark input/table background |

#### Text

| Token                 |     Value | Usage               |
| --------------------- | --------: | ------------------- |
| `text-primary`        | `#0F172A` | Main text           |
| `text-secondary`      | `#475569` | Secondary text      |
| `text-muted`          | `#64748B` | Helper text         |
| `dark-text-primary`   | `#FAFAFA` | Main dark text      |
| `dark-text-secondary` | `#A1A1AA` | Secondary dark text |

#### Borders

| Token                 |     Value | Usage           |
| --------------------- | --------: | --------------- |
| `border-default`      | `#E2E8F0` | Default border  |
| `border-strong`       | `#CBD5E1` | Stronger border |
| `border-focus`        | `#14B8A6` | Focus ring      |
| `dark-border-default` | `#3F3F46` | Dark border     |

---

### 3.2 Semantic Colors

| State   |      Text | Background | Usage                      |
| ------- | --------: | ---------: | -------------------------- |
| Success | `#15803D` |  `#DCFCE7` | Success, paid, completed   |
| Warning | `#B45309` |  `#FEF3C7` | Waiting QC, pending action |
| Danger  | `#B91C1C` |  `#FEE2E2` | Error, failed, delete      |
| Info    | `#1D4ED8` |  `#DBEAFE` | Informational notice       |
| Neutral | `#475569` |  `#F1F5F9` | Draft, inactive, default   |

Dark mode versions may use darker backgrounds with the same semantic meaning.

---

### 3.3 Role Colors

| Role            |   Color | Usage                       |
| --------------- | ------: | --------------------------- |
| Penimbang       |    Blue | Role badge, page accent     |
| Quality Control |   Amber | QC badge and QC status      |
| Kasir           | Emerald | Payment and cashier actions |

Do not apply role colors as full-page backgrounds. Use role colors only as small visual accents, badges, icons, and header labels.

---

### 3.4 Status Badge Colors

| Status                | Visual Style     |
| --------------------- | ---------------- |
| `draft_penimbangan`   | Gray badge       |
| `proses_penimbangan`  | Blue badge       |
| `menunggu_qc`         | Amber badge      |
| `proses_qc`           | Purple badge     |
| `menunggu_pembayaran` | Teal badge       |
| `selesai`             | Green badge      |
| `belum_dinilai`       | Amber/gray badge |
| `sudah_dinilai`       | Green badge      |
| `belum_dibayar`       | Amber badge      |
| `sudah_dibayar`       | Green badge      |
| `dibatalkan`          | Red badge        |

Status color must be consistent across Penimbang, QC, and Kasir pages.

---

### 3.5 Typography

Use system-friendly fonts.

| Token                | Font            |      Size | Weight | Usage                       |
| -------------------- | --------------- | --------: | -----: | --------------------------- |
| `text-page-title`    | Inter/system-ui |      30px |    700 | Main page title             |
| `text-section-title` | Inter/system-ui |      20px |    600 | Card/section title          |
| `text-body`          | Inter/system-ui | 14px–16px |    400 | Normal text                 |
| `text-label`         | Inter/system-ui |      13px |    500 | Form labels                 |
| `text-caption`       | Inter/system-ui |      12px |    400 | Helper/caption text         |
| `text-number`        | Inter/system-ui | 14px–24px |    600 | Weight, price, total values |

Rules:

* Use `tabular-nums` for weight, price, total, and report numbers.
* Use `font-semibold` for important numeric values.
* Avoid overly small text in tables.
* Labels should be clear and placed above inputs.

---

### 3.6 Spacing

Base spacing unit: 4px.

| Token     | Value | Usage                    |
| --------- | ----: | ------------------------ |
| `space-1` |   4px | Micro spacing            |
| `space-2` |   8px | Badge gap, small gap     |
| `space-3` |  12px | Input/button padding     |
| `space-4` |  16px | Default internal spacing |
| `space-5` |  20px | Card padding small       |
| `space-6` |  24px | Card/section spacing     |
| `space-8` |  32px | Page section gap         |

Rules:

* Page padding: `px-6 py-6`, desktop `lg:px-8 lg:py-8`.
* Card padding: 20px–24px.
* Section spacing: 24px–32px.
* Form field gap: 16px.
* Table cell padding: 12px–16px.

---

### 3.7 Border Radius

| Token        | Value | Usage                  |
| ------------ | ----: | ---------------------- |
| `radius-sm`  |   6px | Small badges           |
| `radius-md`  |  10px | Inputs, small buttons  |
| `radius-lg`  |  12px | Buttons, compact cards |
| `radius-xl`  |  16px | Cards and panels       |
| `radius-2xl` |  20px | Large cards            |

Rules:

* Inputs and buttons should generally use `rounded-xl`.
* Cards should use `rounded-2xl`.
* Print pages should avoid excessive border radius.

---

## 4. Layout System

### 4.1 Global Layout

Every authenticated page should follow this structure:

1. Role/menu label.
2. Page title.
3. Short page description.
4. Main action button if needed.
5. Summary cards if relevant.
6. Main table/form/detail card.

Recommended wrapper:

```html
<div class="px-6 py-6 lg:px-8 lg:py-8">
    <div class="mx-auto max-w-7xl space-y-8">
        <!-- page content -->
    </div>
</div>
```

Rules:

* Use `max-w-7xl` for dashboard/table-heavy pages.
* Use `max-w-4xl` or `max-w-5xl` for forms.
* Avoid placing content directly against screen edges.
* Use `space-y-6` or `space-y-8` between major sections.

---

### 4.2 Page Header

Each page should include:

* Small label: role/menu context.
* Main title.
* Short description.
* Primary action button on the right when needed.

Example page header text:

| Page                | Label                | Title                 |
| ------------------- | -------------------- | --------------------- |
| Penimbang dashboard | Menu Penimbang       | Dashboard Penimbang   |
| Transaksi           | Menu Penimbang       | Transaksi Penimbangan |
| QC list             | Menu Quality Control | Penilaian QC          |
| Payment             | Menu Kasir           | Pembayaran            |
| Report              | Menu Kasir           | Laporan Pembayaran    |

---

## 5. Component Guidelines

### 5.1 Buttons

Button variants:

| Variant   | Usage                                             |
| --------- | ------------------------------------------------- |
| Primary   | Main action: Simpan, Bayar, Terapkan              |
| Secondary | Back, Detail, Cancel                              |
| Success   | Final positive action: Selesai, Bayar, Konfirmasi |
| Warning   | Pending action, QC-related action                 |
| Danger    | Delete, cancel, destructive action                |
| Print     | Print receipt, print queue number                 |

Rules:

* Primary button should be visually dominant.
* Avoid too many primary buttons in one section.
* Destructive actions must use danger styling.
* Print buttons should be hidden during print using `print:hidden`.
* Icon-only buttons must include accessible labels.

---

### 5.2 Forms

Form rules:

* Labels must always be visible.
* Do not rely on placeholder as the only label.
* Inputs should use consistent height and border radius.
* Validation messages must appear close to the related field.
* Important numeric inputs must show units such as `kg` or `Rp`.
* Submit button should be visually clear.

#### Numeric Input Rule

For weight, price, and currency fields, native browser spinner/scroll controls must not be visible.

This applies to inputs such as:

* Berat timbangan pertama.
* Tara / berat setelah bongkar.
* Berat bersih.
* Harga per kg.
* Potongan kasbon.
* Total pembayaran.

Preferred implementation:

* Use `type="text"` with `inputmode="decimal"` or `inputmode="numeric"`.
* Keep validation in backend and frontend where available.
* Display unit suffix such as `kg` or `Rp` in a separate visual addon.

Acceptable CSS fallback if `type="number"` must be used:

```css
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}
```

UX rule:

* Do not allow mouse wheel scrolling to accidentally change weight or price values.
* Numeric fields should feel like normal text inputs but still validate as numbers.
* Unit labels such as `kg` must be visually separated and not typed manually by the user.

Example visual pattern:

```text
[ 1233                         kg ]
```

The user types only the numeric value. The unit is shown by the UI.

---

### 5.3 Cards

Cards are used for:

* Dashboard summary.
* Transaction detail.
* QC result.
* Fuzzy detail.
* Payment summary.
* Kasbon summary.
* Report summary.

Card rules:

* Use white or dark card background depending on color mode.
* Use subtle border.
* Use `rounded-2xl`.
* Use internal spacing of 20px–24px.
* Each card should have a clear title or purpose.
* Avoid nesting too many cards inside cards.

---

### 5.4 Tables

Tables are used heavily in this project.

Rules:

* Use `overflow-x-auto` for wide tables.
* Header should be visually distinct.
* Row borders should be subtle.
* Status should use badges.
* Numbers should align right.
* Actions should usually be on the right.
* Empty state must be shown when there is no data.
* Avoid too many buttons in each row.

Number columns that must align right:

* Berat kotor.
* Berat bersih.
* Harga per kg.
* Potongan berat.
* Potongan kasbon.
* Total pembayaran.
* Sisa hutang.

---

### 5.5 Badges

Badges are used for:

* Role.
* Transaction status.
* QC status.
* Payment status.
* Kasbon status.
* Fuzzy result category.

Rules:

* Badge text should be short.
* Badge color must follow status mapping.
* Avoid using plain text status without badge.
* Use consistent casing.

---

### 5.6 Alerts

Use alerts for:

* Success after saving.
* Error validation.
* Warning when item is incomplete.
* Information about bypass fuzzy or pending QC.

Alert types:

| Type    | Usage                          |
| ------- | ------------------------------ |
| Success | Data saved successfully        |
| Warning | Still pending, incomplete data |
| Danger  | Error or invalid action        |
| Info    | Explanation or guidance        |

---

## 6. Page-Specific Guidelines

### 6.1 Login Page

Rules:

* Centered login card.
* Simple brand/system title.
* Username/password inputs clear.
* No excessive decoration.
* Error message displayed clearly.

Login page should feel professional and consistent with internal operational system.

---

### 6.2 Penimbang Pages

#### Dashboard Penimbang

Must show:

* Total transaksi harian.
* Total berat bersih harian if available.
* Recent transactions.
* Quick navigation to transactions and customers.

Design rules:

* Summary cards at top.
* Recent transaction table below.
* Status badge for each transaction.

#### Timbang Pertama

Must show:

* Customer identity.
* Vehicle type.
* Plate number.
* Initial weight.
* Paper types selected.

Design rules:

* Weight input must be large and clear.
* Weight input must not show browser spinner.
* Unit `kg` must be visible.
* Primary action button: Simpan Timbangan Pertama.

#### Timbang Bertahap

This is a critical operational page.

Must show:

* Current transaction summary.
* Previous weight / berat sebelum bongkar.
* Input tara / berat setelah bongkar.
* Calculated net weight preview.
* Current paper type being weighed.
* Staged weighing history.
* Remaining items not yet weighed.

Design rules:

* Net weight preview should be prominent.
* Use explanatory helper text.
* Do not make the page too dense.
* Show clear status for each paper type: belum ditimbang / sudah ditimbang.

Important logic note for UI copy:

```text
Berat bersih dihitung dari selisih berat sebelumnya dan berat setelah bongkar.
```

#### Detail Transaksi Penimbang

Must show:

* Transaction code.
* Customer.
* Vehicle.
* Plate number.
* Detail paper types.
* Weight results.
* QC/payment status.
* Print queue number button when allowed.

Design rules:

* Use card sections.
* Weight data should use tabular numbers.
* Print button must be easy to find.

#### Print Nomor Antrian

Rules:

* White background.
* Black text.
* No sidebar/navbar.
* Queue number must be prominent.
* Show transaction code, customer, vehicle, plate, and total weight if available.
* Hide buttons during print.

---

### 6.3 Quality Control Pages

#### Daftar Penilaian QC

Must show:

* Transactions/items waiting for QC.
* Customer name.
* Paper type.
* Net weight.
* Status.

Rules:

* Items `<= 100 kg` should not appear in QC.
* Each row should have clear action button.

#### Form Penilaian QC

Must show:

* Transaction detail.
* Paper type.
* Net weight.
* Quality score input.
* QC notes if available.

Rules:

* Quality score input should be simple.
* Use helper text for scale meaning.
* Save action must be clear.

#### Hasil Fuzzy

Must show:

* Final bobot ketidaklayakan.
* Percentage deduction.
* Weight deduction.
* Eligible weight / berat layak.
* Fuzzification section.
* Inference section.
* Defuzzification section.
* Active rules only.

Rules:

* Separate fuzzy steps into cards.
* Do not show all inactive rules as main content.
* Use tables only when needed.
* Numeric values must be formatted cleanly.

---

### 6.4 Kasir Pages

#### Daftar Pembayaran

Must show:

* Transactions ready to pay.
* Customer.
* Total net weight.
* Fuzzy/payment readiness.
* Action button.

Rules:

* Use status badge: siap bayar / belum siap.
* Payment action should be clear.

#### Form Pembayaran

Must show:

* Transaction summary.
* Detail paper types.
* Price per kg input.
* Kasbon deduction input.
* Total transaction.
* Total paid to customer.
* Remaining debt if applicable.

Rules:

* Currency numbers must be prominent.
* Total paid to customer should be visually strongest.
* Kasbon deduction must be clearly separated from item subtotal.
* Price and kasbon inputs must not show browser spinner controls.
* Use `Rp` prefix visually, not typed manually.

#### Kasbon / Hutang

Must show:

* Customer.
* Total debt.
* Total paid.
* Remaining debt.
* Payment history.

Rules:

* Debt status must use clear badge.
* Danger/warning colors only when action is required.
* Avoid making debt pages look like error pages.

#### Laporan Kasir

Must show:

* Date filter.
* Summary cards.
* Payment table.
* Detail button.
* Print option.

Rules:

* Date filter must be easy to use.
* Summary cards must show total payment, total weight, total transaction, and total paid to customer.
* Print actions should be hidden when printing.

#### Detail Pembayaran

Must show:

* Payment header.
* Customer and transaction info.
* Item details.
* Kasbon deduction.
* Total paid.
* Print button.

Rules:

* Use sections/cards.
* Print button top-right.
* Currency values right-aligned.

#### Print Pembayaran

Rules:

* White background.
* Black text.
* No navbar/sidebar.
* No unnecessary color.
* Business receipt style.
* Signature area for cashier and customer if needed.
* Automatically open print dialog if current behavior already does that.

---

## 7. Print Layout Rules

Print pages:

* `print-antrian`
* `print-pembayaran`
* `print-laporan`

Rules:

* Use plain white background.
* Use black or dark gray text.
* Hide navigation and buttons.
* Avoid shadows and gradients.
* Use readable font size.
* Use `@media print`.
* Use `print:hidden` for non-print elements.

Do not use dark mode styling on printed pages.

---

## 8. Loading, Empty, and Error States

### 8.1 Empty State

Every empty table/list must show an empty state.

Examples:

* "Belum ada transaksi pada periode ini."
* "Belum ada barang yang menunggu QC."
* "Belum ada data pembayaran."
* "Belum ada riwayat kasbon."
* "Belum ada detail barang."

Empty state should be centered inside the card/table area.

---

### 8.2 Error State

Error copy must be clear and actionable.

Examples:

* "Masih ada barang yang belum ditimbang."
* "Fuzzy belum lengkap untuk transaksi ini."
* "Data pembayaran tidak ditemukan."
* "Pelanggan masih memiliki transaksi aktif."
* "Potongan kasbon melebihi sisa hutang."

---

### 8.3 Success State

Use success alert after important actions.

Examples:

* "Timbangan pertama berhasil disimpan."
* "Timbang bertahap berhasil disimpan."
* "Penilaian QC berhasil disimpan."
* "Pembayaran berhasil disimpan."
* "Kasbon berhasil diperbarui."

---

## 9. Accessibility Rules

Minimum expectations:

* Every input has a visible label.
* Every button has readable text or accessible label.
* Focus state must be visible.
* Color contrast must be readable.
* Tables must be horizontally scrollable on mobile.
* Do not use color alone to communicate status; include badge text.
* Buttons must be large enough for mouse and touch use.
* Print pages must remain readable without color.

---

## 10. Copywriting Standards

Use Indonesian operational language.

Preferred terms:

| Use                 | Avoid                    |
| ------------------- | ------------------------ |
| Simpan              | Submit                   |
| Kembali             | Back                     |
| Detail              | View                     |
| Cetak               | Print if Indonesian page |
| Bayar               | Process                  |
| Selesai Penimbangan | Complete                 |
| Timbang Bertahap    | Step Weighing            |
| Menunggu QC         | Pending QC               |
| Menunggu Pembayaran | Pending Payment          |
| Berat Bersih        | Net Weight               |
| Potongan Kasbon     | Debt Deduction           |

Rules:

* Use short and direct labels.
* Avoid technical English unless unavoidable.
* Avoid vague button text like "OK" or "Process".
* Use action-specific text.

---

## 11. AI Agent Safety Rules

AI agents must follow these rules strictly.

### Allowed

AI may modify:

* Blade view files.
* CSS/Tailwind classes.
* Shared layout/component view files.
* Print view styling.
* `resources/css/app.css` if needed.

### Not Allowed

AI must not modify:

* `routes/web.php`
* `app/Http/Controllers`
* `app/Services`
* `database/migrations`
* `.env`
* database schema
* model relationships
* business logic
* fuzzy formulas
* payment formulas
* staged weighing formulas
* kasbon logic
* validation logic unless explicitly requested

### Form Safety

AI must not change:

* `name` attributes.
* `action` route.
* HTTP method.
* `@csrf`.
* `@method('PUT')`, `@method('DELETE')`.
* Hidden inputs.
* Existing Blade variable names.
* Conditional logic that controls button visibility.

### Route Safety

AI must not change:

* `route(...)` names.
* route parameters.
* URL structures.
* role-based navigation logic.

### Numeric Input Safety

AI must ensure:

* Weight and currency inputs do not show browser spinner controls.
* Weight inputs show `kg` as unit.
* Currency inputs show `Rp` as prefix or visual label.
* Numeric validation remains intact.
* Scroll wheel must not accidentally change weight or price values.

### If Unsure

If a UI change requires modifying backend logic, the AI agent must stop and ask for confirmation.

---

## 12. Implementation Order for UI Refresh

Do not redesign all pages at once.

Recommended order:

1. Global layout and navigation.
2. Shared components: buttons, cards, badges, tables, forms.
3. Login page.
4. Penimbang pages.
5. QC pages.
6. Kasir pages.
7. Print pages.
8. Responsive cleanup.
9. Dark mode cleanup if needed.

Each stage must be tested before continuing.

---

## 13. Manual QA Checklist

After UI changes, test:

### Authentication

* Login as Penimbang.
* Login as QC.
* Login as Kasir.
* Logout.

### Penimbang

* Dashboard opens.
* Pelanggan page opens.
* Timbangan pertama works.
* Timbang bertahap works.
* Selesai penimbangan works.
* Print antrian works.

### QC

* QC list opens.
* QC form works.
* Fuzzy result displays.
* QC history works.

### Kasir

* Payment list opens.
* Payment form works.
* Kasbon deduction works.
* Report page opens.
* Detail payment opens.
* Print payment works.

### Print

* Print antrian readable.
* Print pembayaran readable.
* Buttons hidden while printing.
* Navbar/sidebar hidden while printing.

### Responsive

* Tables scroll horizontally on small screens.
* Forms remain readable on tablet/mobile.
* Buttons do not overflow.

---

## 14. Quick Reference

| Decision         | Rule                                               |
| ---------------- | -------------------------------------------------- |
| Main UI style    | Clean operational dashboard                        |
| Main accent      | Teal/Emerald                                       |
| Page background  | Light gray / dark zinc                             |
| Card style       | White/dark card, subtle border, rounded-2xl        |
| Table style      | Clear header, subtle border, right-aligned numbers |
| Button style     | Rounded-xl, clear hierarchy                        |
| Status display   | Always use badge                                   |
| Weight input     | No spinner controls, show `kg` unit                |
| Currency input   | No spinner controls, show `Rp`                     |
| Print pages      | White background, black text, no navigation        |
| AI scope         | UI only unless explicitly instructed               |
| Logic changes    | Not allowed                                        |
| Route changes    | Not allowed                                        |
| Database changes | Not allowed                                        |

---

## 15. Project-Specific Non-Negotiables

1. Do not alter the staged weighing formula.
2. Do not alter fuzzy formulas or rules.
3. Do not alter payment calculation logic.
4. Do not alter kasbon deduction logic.
5. Do not alter role access behavior.
6. Do not alter route names.
7. Do not alter form input names.
8. Do not alter database schema.
9. Do not read or modify `.env`.
10. Do not show number input spinner controls on weight or currency fields.

---

End of DESIGN.md.

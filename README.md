# WC Dynamic Templates

# WC Dynamic Templates / Δυναμικά Πρότυπα WC

A powerful WordPress plugin that lets you define dynamic, reusable HTML templates with WooCommerce attribute shortcodes. Ideal for product technical tables, performance data, or modular product descriptions.
Ένα ισχυρό πρόσθετο για WordPress που σας επιτρέπει να δημιουργείτε δυναμικά, επαναχρησιμοποιούμενα HTML πρότυπα με shortcodes χαρακτηριστικών του WooCommerce. Ιδανικό για τεχνικούς πίνακες προϊόντων, δεδομένα απόδοσης ή περιγραφές μεμονωμένων στοιχείων.

---

## 🧩 Plugin Features / Χαρακτηριστικά

- Custom Post Type: **WC Templates** / Προσαρμοσμένος τύπος περιεχομένου: **Πρότυπα WC**
- Shortcode: `[wc_template id="123"]`
- Conditional rendering: Show fields only if product attribute exists / Προβολή μόνο αν υπάρχει τιμή
- Visibility control: Based on user role / Έλεγχος ορατότητας ανά ρόλο χρήστη
- Auto-injection into products by category / Αυτόματη εμφάνιση βάσει κατηγορίας προϊόντος
- Grouping templates with unified output style / Ομαδοποίηση προτύπων με κοινή εμφάνιση
- Attribute shortcodes: `[pa value="..."]` και `[pa_if value="..."]...[/pa_if]`
- Inline CSS per template / Προσαρμοσμένο CSS ανά πρότυπο
- Live shortcode tester tool / Εργαλείο live δοκιμής shortcodes
- Preview templates as in product / Προεπισκόπηση όπως στο προϊόν

---

## 🚀 How to Use / Οδηγίες Χρήσης

### 1. Create a Template / Δημιουργία Προτύπου
- Go to **WP Admin → WC Templates → Add New**
- Μεταβείτε: **Διαχείριση WP → WC Templates → Προσθήκη Νέου**
- Use the editor to write your HTML structure (e.g. tables, divs)
- Χρησιμοποιήστε HTML και shortcodes για πίνακες, divs, κ.λπ.
- Optionally define:
  - Custom CSS (per template) / Προσαρμοσμένο CSS (μόνο για αυτό το πρότυπο)
  - Visibility by user role / Ορατότητα ανά ρόλο χρήστη
  - Product categories where it auto-applies / Κατηγορίες προϊόντων για αυτόματη εμφάνιση

---

## 🔧 Available Shortcodes / Διαθέσιμα Shortcodes

### 🔹 `[wc_template id="123"]`
Displays the template with ID 123.
Εμφανίζει το πρότυπο με ID 123.

---

### 🔹 `[pa value="slug"]`
Returns the value of a product attribute.
Επιστρέφει την τιμή ενός χαρακτηριστικού προϊόντος.
**Example / Παράδειγμα:**
```html
[pa value="uw"] → "1.2"
```

---

### 🔹 `[pa_if value="slug"]...[/pa_if]`
Renders inner content only if the product has a value for the given attribute.
Εμφανίζει περιεχόμενο μόνο αν υπάρχει τιμή για το χαρακτηριστικό.
**Example / Παράδειγμα:**
```html
[pa_if value="sound"]<tr><td>Sound Reduction</td><td>[pa value="sound"] db</td></tr>[/pa_if]
```

---

### 🔹 `[wcdt_if_pa value="slug"]...[/wcdt_if_pa]`
Improved version of `[pa_if]`, supports nested blocks.
Βελτιωμένη έκδοση του `[pa_if]`, υποστηρίζει εμφωλευμένα μπλοκ.

---

### 🔹 `[wc_template_group id="123"]`
Renders all templates assigned to the given group.
Εμφανίζει όλα τα πρότυπα που ανήκουν σε συγκεκριμένη ομάδα.

---

## 🧪 Tools / Εργαλεία

### 🧪 Shortcode Tester / Δοκιμαστής Shortcode
- Go to **WP Admin → WC Templates → Shortcode Tester**
- Πηγαίνετε: **Διαχείριση WP → WC Templates → Δοκιμαστής Shortcode**
- Paste shortcode, click **Run** / Επικολλήστε shortcode και πατήστε **Εκτέλεση**

---

## 🗃️ Auto Apply to Product Categories / Αυτόματη Εμφάνιση σε Κατηγορίες

While editing a template, select WooCommerce product categories.
Κατά την επεξεργασία ενός προτύπου, επιλέξτε τις κατηγορίες προϊόντων WooCommerce.

---

## 👁️ Visibility Per Role / Ορατότητα ανά Ρόλο

Choose visibility settings:
Επιλέξτε ρυθμίσεις ορατότητας:
- Public / Δημόσιο
- Logged-in / Συνδεδεμένοι
- Specific Roles / Συγκεκριμένοι ρόλοι

---

## 🎨 Custom CSS Per Template / Προσαρμοσμένο CSS

Each template supports separate inline CSS.
Κάθε πρότυπο υποστηρίζει δικό του CSS ενσωματωμένο.

---

## 📄 Template Groups / Ομάδες Προτύπων

- Group templates into categories / Ομαδοποιήστε πρότυπα σε κατηγορίες
- Shared styling / Κοινό στυλ εξόδου
- Shortcode: `[wc_template_group id="..."]`

---

## 🛠️ Development Notes / Σημειώσεις Ανάπτυξης

This plugin supports GitHub updates.
Υποστηρίζονται αυτόματες ενημερώσεις μέσω GitHub (`plugin-update-checker`).

---

For bugs, suggestions, or contributions, visit:  
Για σφάλματα ή βελτιώσεις, επισκεφθείτε:  
🔗 https://github.com/bkerest/wc-templates



wc-templates/
├── wc-dynamic-templates.php  <-- Main plugin file
├── includes/
│   ├── post-type-template.php
│   ├── shortcode-renderer.php
│   ├── template-visibility.php
│   ├── meta-box-visibility.php
│   ├── template-style-loader.php
│   ├── template-inline-css.php
│   ├── attribute-shortcodes.php
│   ├── template-groups.php
│   ├── template-group-style.php
│   ├── template-preview.php
│   ├── template-taxonomy.php
│   ├── shortcode-tester.php
│   ├── template-shortcode-copy.php
│   ├── template-auto-assign.php
│   ├── template-conditional-shortcodes.php
├── plugin-update-checker/
│   ├── plugin-update-checker.php
│   └── Puc/
│       └── v5p6/
├── assets/
│   └── css/


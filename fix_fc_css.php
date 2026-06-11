<?php
$cssFile = __DIR__ . '/resources/css/app.css';
$lines   = file($cssFile);

// Find the .menu-grid block start (after carousel area, i.e. after line 3000)
// and the "Branch cards on dashboard" comment which marks the end of fc- zone
$start = $end = -1;
foreach ($lines as $i => $line) {
    if (strpos($line, '.menu-grid {') !== false && $i > 3000 && $start === -1) {
        $start = $i;
    }
    if (strpos($line, 'Branch cards on dashboard') !== false && $start > 0 && $end === -1) {
        $end = $i;
        break;
    }
}

if ($start < 0 || $end < 0) {
    echo "ERROR: boundaries not found (start=$start, end=$end)\n";
    exit(1);
}

echo "Replacing lines $start to $end\n";

$newBlock = <<<'CSSBLOCK'
    /* ─────────────────────────────────────────────────────────
       MENU GRID
    ───────────────────────────────────────────────────────── */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
        align-items: stretch;
    }

    /* ─────────────────────────────────────────────────────────
       FOOD CARD  (fc- prefix)
       Key technique:
         .menu-card        → display:flex; flex-direction:column
         .fc-body          → flex:1; display:flex; flex-direction:column
         .fc-price-row     → margin-top:auto  (pushes price to bottom)
         .fc-actions       → flex-shrink:0    (buttons never get squeezed)
       This guarantees every card is equal height and buttons
       always sit at the very bottom, no matter content length.
    ───────────────────────────────────────────────────────── */

    /* 1. Card shell */
    .menu-card {
        display: flex;
        flex-direction: column;
        width: 100%;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #eeeeee;
        box-shadow: 0 2px 12px rgb(0 0 0 / 7%);
        overflow: hidden;
        cursor: pointer;
        transition: transform 200ms ease,
                    box-shadow 200ms ease,
                    border-color 200ms ease;
    }

    .menu-card:hover {
        transform: translateY(-5px);
        border-color: #fdba74;
        box-shadow: 0 8px 28px rgb(255 107 0 / 15%);
    }

    /* 2. Fixed-height image — identical across every card */
    .fc-image-wrap {
        position: relative;
        width: 100%;
        height: 150px;
        flex-shrink: 0;
        overflow: hidden;
        background: #fff7ed;
    }

    .fc-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        transition: transform 380ms ease;
    }

    .menu-card:hover .fc-image {
        transform: scale(1.06);
    }

    /* Subtle bottom scrim on image */
    .fc-image-wrap::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgb(0 0 0 / 22%) 0%, transparent 52%);
        pointer-events: none;
    }

    /* 3. Orange category badge — top-left corner of image */
    .fc-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        height: 22px;
        padding: 0 9px;
        border-radius: 999px;
        background: #ff6b00;
        color: #ffffff;
        font-size: 0.61rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        white-space: nowrap;
        max-width: calc(100% - 20px);
        overflow: hidden;
        text-overflow: ellipsis;
        box-shadow: 0 2px 6px rgb(255 107 0 / 40%);
    }

    /* 4. Card body — flex column; content grows, buttons stay bottom */
    .fc-body {
        display: flex;
        flex-direction: column;
        flex: 1;                  /* fills remaining card height */
        padding: 12px 13px 0;
        min-height: 0;
    }

    /* 5. Tag pill below image */
    .fc-tag {
        display: inline-flex;
        align-self: flex-start;
        align-items: center;
        height: 20px;
        padding: 0 8px;
        border-radius: 999px;
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #fed7aa;
        font-size: 0.63rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        white-space: nowrap;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        flex-shrink: 0;
        margin-bottom: 8px;
    }

    /* 6. Food name — 2-line clamp keeps ALL cards same text height */
    .fc-name {
        margin: 0 0 5px;
        font-size: 0.91rem;
        font-weight: 800;
        line-height: 1.35;
        color: #111827;
        /* clamp to exactly 2 lines */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        /* reserve 2-line height even if text is only 1 line */
        min-height: calc(0.91rem * 1.35 * 2);
        flex-shrink: 0;
    }

    /* 7. Branch name */
    .fc-branch {
        display: flex;
        align-items: center;
        gap: 4px;
        margin: 0 0 0;
        font-size: 0.73rem;
        font-weight: 500;
        color: #9ca3af;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex-shrink: 0;
    }

    .fc-branch svg {
        flex-shrink: 0;
        opacity: 0.65;
    }

    /* 8. Price row — margin-top:auto pushes it to the bottom of fc-body */
    .fc-price-row {
        margin-top: auto;
        padding: 8px 0 10px;
        flex-shrink: 0;
    }

    .fc-price {
        display: block;
        font-size: 1.05rem;
        font-weight: 900;
        color: #ff6b00;
        letter-spacing: -0.02em;
        line-height: 1;
    }

    /* 9. Button row — divider + always at card bottom */
    .fc-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 13px 13px;
        margin-left: -13px;
        margin-right: -13px;
        border-top: 1px solid #f3f4f6;
        flex-shrink: 0;
    }

    /* 10. Base button styles */
    .fc-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        height: 38px;
        border-radius: 12px;
        border: none;
        font-size: 0.76rem;
        font-weight: 600;
        line-height: 1;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
        transition: background 150ms ease,
                    color 150ms ease,
                    border-color 150ms ease,
                    box-shadow 150ms ease,
                    transform 150ms ease;
    }

    .fc-btn svg {
        flex-shrink: 0;
        pointer-events: none;
    }

    /* 11. Branch — secondary, fixed 40% */
    .fc-btn-secondary {
        flex: 0 0 calc(40% - 5px);
        background: #f9fafb;
        color: #6b7280;
        border: 1.5px solid #e5e7eb;
        font-weight: 500;
        padding: 0 10px;
    }

    .fc-btn-secondary:hover {
        background: #fff7ed;
        color: #ea580c;
        border-color: #fdba74;
        transform: translateY(-1px);
    }

    /* 12. Add to Cart — primary orange, fills remaining width */
    .fc-btn-primary {
        flex: 1;
        background: #ff6b00;
        color: #ffffff;
        border: 1.5px solid transparent;
        font-weight: 700;
        padding: 0 10px;
        box-shadow: 0 3px 10px rgb(255 107 0 / 26%);
    }

    .fc-btn-primary:hover {
        background: #e05f00;
        box-shadow: 0 6px 16px rgb(255 107 0 / 40%);
        transform: translateY(-2px);
    }

    .fc-btn-primary:active {
        transform: translateY(0);
        box-shadow: 0 2px 6px rgb(255 107 0 / 20%);
    }

    /* Legacy .tag-row (used outside food cards elsewhere) */
    .tag-row {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        padding: 8px 16px 0;
        margin-top: 0;
    }

    .tag-row small {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 999px;
        background: #fff7ed;
        color: var(--brand);
        font-weight: 700;
        border: 1px solid #fed7aa;
    }

CSSBLOCK;

$before = array_slice($lines, 0, $start);
$after  = array_slice($lines, $end);

$output = implode('', $before) . $newBlock . implode('', $after);
file_put_contents($cssFile, $output);
echo "Done. Bytes written: " . strlen($output) . "\n";

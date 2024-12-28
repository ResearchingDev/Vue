// Dynamically import all SVGs in the assets folder
const icons = import.meta.glob('@/assets/svg/*.svg', { eager: true });

// Function to get the correct SVG path
export function getIconPath(iconName) {
    const path = icons[`@/assets/svg/${iconName}.svg`];
    if (!path) {
        console.warn(`Icon "${iconName}" not found.`);
    }
    return path;
}

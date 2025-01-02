
// Function to get the correct SVG path
export function getIconPath(iconName) {
    const path = `@/assets/svg/icon-sprite.svg`;
    if (!path) {
        console.log(`Icon "${iconName}" not found.`);
    }
    console.log(path);
    return path;
}

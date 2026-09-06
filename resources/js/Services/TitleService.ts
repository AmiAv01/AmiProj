export function editDetailTitle(str: string | null | undefined): string | null | undefined {
    if (!str) {
        return str;
    }

    return str[0].toUpperCase() + str.slice(1).toLowerCase();
}

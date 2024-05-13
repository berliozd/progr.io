export async function sortProjectChildren(project) {
    project.notes.sort((noteA, noteB) => noteA.order > noteB.order ? 1 : -1);
    project.competitors.sort((competitorA, competitorB) => competitorA.pivot.order > competitorB.pivot.order ? 1 : -1);
}
export async function sortNotes(project) {
    project.notes.sort((noteA, noteB) => noteA.order > noteB.order ? 1 : -1);
    project.competitors.sort((competitorA, competitorB) => competitorA.order > competitorB.order ? 1 : -1);
    for (let competitorIndex in project.competitors) {
        project.competitors[competitorIndex].notes.sort((noteA, noteB) => noteA.order > noteB.order ? 1 : -1);
    }
}
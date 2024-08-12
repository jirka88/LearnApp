
interface prefixGroup {
    prefix: string,
    group: 'groupAdmin' | 'groupSubjects' | 'groupShare'
}

const prefixGroups : prefixGroup[] = [
    {
        prefix: 'dashboard/admin',
        group: 'groupAdmin'
    },
    {
        prefix: 'dashboard/manager/subject',
        group: 'groupSubjects'
    },
    {
        prefix: 'dashboard/sharing',
        group: 'groupShare'
    }
]
export default prefixGroups;

const rules = {
    required: (value) => !!value || 'Nutné vyplnit!',
    firstnameLength: (v) => v.length < 25 || 'Jméno je příliš dlouhé!',
    lastnameLength: (v) => v.length < 50 || 'Příjmení je příliš dlouhé!',
    emailLength: (v) => v.length < 64 || 'Email je příliš dlouhý!',
    email: (v) =>
        /^(([^<>()[\]\\.,;:\s@']+(\.[^<>()\\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
            v
        ) || 'E-mail musí být validní!',
    password: (v) => {
        const missingElements = []
        if (v.length < 8) {
            missingElements.push('více než 8 znaků')
        }
        if (!/(?=.*\d)/.test(v)) {
            missingElements.push('číslici')
        }
        if (!/[!@#$%^&*]/.test(v)) {
            missingElements.push('speciální znak')
        }
        if (!/(?=.*[a-z])/.test(v)) {
            missingElements.push('malé písmeno')
        }
        if (!/(?=.*[A-Z])/.test(v)) {
            missingElements.push('velké písmeno')
        }
        if (missingElements.length > 0) {
            return `Heslo musí obsahovat ${missingElements.join(', ')}!`
        } else {
            return true
        }
    },
    oldPassword: (v) => v.length > 0 || 'Nutné zadat staré heslo!',
    minSubjectLength: (value) =>
        value.length > 2 || 'Předmět musí mít delší název!',
    chapterNameLength: (value) =>
        value.length <= 20 || 'Název je příliš dlouhý!',
    nameLength: (value) => value.length <= 20 || 'Název je příliš dlouhý!',
    perexLength: (value) => value.length <= 50 || 'Perex je příliš dlouhý!'
}
export default rules

# LearnApp - Aplikace pro tvorbu online poznámek.

# Funkce:

## Registrace

- Umožnění registrace pomocí emailu
- Hashování hesla
- Nastavení typ účtu

## přihlášení

- Ochrana proti spam útokům
- Validace údajů

## Běžný uživatel

- Možnost spravovat svůj účet
- Možnost vybrat profilovou fotku
- Možnost změnit heslo.
- Možnost měnit jazyk CZ/EN

## Organizace

- Možnost vytvořit jednotlivé organizace = (slouží ke seskupení poznámek)
- Nastavení jména a ikony organizace.
- Možnost sdílet organizaci

#### Sdílení

- Lze organizaci nasdílet určitému uživateli
- **Tři základní oprávnění**: číst/zápis/plná kontrola
- Po sdílení určitému uživateli musí uživatel potvrdit sdílení
- jednotlivé sdílení lze dále spravovat

## Poznámky

- Editovatelné pomocí wysiwyq editoru

## Administrátor

- Umožněno spravovat uživatelé i jejich organizace
- Možnost deaktivovat uživatele
- Možnost vytvořit uživatele bez nutnosti registrace
- Náhlížení do grafu a statisktik (Dashboard)
- Přístup ke globálním nastavení aplikace

## Licence

- Standartní/Standartní+/Premium
- Jedná se o omezení vytváření organizací a poznámek

## Globální věci

- Role: Admin, Operátor, Tester
- Chráněné routy podle rolí a uživatelů
- Automatizované testy (Features - PhpUnit)
- Filtrace dat (Default,ASC,DESC) 

# TO DO

- Optimalizace komponent
- Přidání vlastních statistik běžnému uživateli
- Přidání více možností pro testera
- Možnost zapomenuté heslo
- Lepší ukládání obrázků v poznámkách


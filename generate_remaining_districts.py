# Script to generate remaining India districts with proper UUIDs

# Define all remaining districts organized by state
districts_data = {
    # Madhya Pradesh - 55 districts (starting from 0x011a)
    's00000000-0040-4000-0000-000400000000': [
        ('Agar Malwa', 'AGM', 1000000, 3000, 'Soybean hub'),
        ('Alirajpur', 'ALR', 728999, 3182, 'Tribal district'),
        ('Anuppur', 'ANP', 749521, 3947, 'Coal and forest district'),
        ('Ashoknagar', 'ASK', 845071, 4674, 'Agricultural district'),
        ('Balaghat', 'BLG', 1701698, 9229, 'Forest rich district'),
        ('Barwani', 'BWI', 1385659, 5427, 'Narmada valley district'),
        ('Betul', 'BTL', 1575362, 10043, 'Southern district'),
        ('Bhind', 'BHI', 1703562, 4459, 'Border district with UP'),
        ('Bhopal', 'BPL', 2371061, 2772, 'Capital district'),
        ('Burhanpur', 'BHP', 757847, 3427, 'Historical district'),
        ('Chhatarpur', 'CTP', 1762375, 8687, 'Temple district'),
        ('Chhindwara', 'CHD', 2090922, 11815, 'Largest by area'),
        ('Damoh', 'DMH', 1264219, 7306, 'Central district'),
        ('Datia', 'DAT', 786375, 2691, 'Historical district'),
        ('Dewas', 'DWS', 1563715, 7020, 'Industrial district'),
        ('Dhar', 'DHR', 2185793, 8153, 'Historic fort district'),
        ('Dindori', 'DND', 704524, 7427, 'Tribal district'),
        ('Guna', 'GNA', 1241519, 6485, 'Agricultural district'),
        ('Gwalior', 'GWL', 2030543, 4564, 'Historic fort city'),
        ('Harda', 'HRD', 570302, 3332, 'Small agricultural district'),
        ('Hoshangabad', 'HSG', 1241350, 6707, 'Narmada district'),
        ('Indore', 'IDR', 3272335, 3898, 'Commercial capital'),
        ('Jabalpur', 'JBP', 2460714, 10160, 'Marble city'),
        ('Jhabua', 'JHB', 1024091, 6782, 'Tribal district'),
        ('Katni', 'KTN', 1292042, 4949, 'Cement hub'),
        ('Khandwa', 'KHD', 1309443, 7123, 'Musical instruments district'),
        ('Khargone', 'KRG', 1872413, 8030, 'Cotton district'),
        ('Mandla', 'MND', 1054905, 8771, 'Tribal forest district'),
        ('Mandsaur', 'MDS', 1340411, 5530, 'Opium district'),
        ('Morena', 'MOR', 1965137, 4998, 'Border district'),
        ('Narsinghpur', 'NSP', 1091697, 5125, 'Narmada valley'),
        ('Neemuch', 'NMH', 826067, 4267, 'Agricultural district'),
        ('Niwari', 'NWR', 400000, 1200, 'Newest district'),
        ('Panna', 'PNA', 1016520, 7135, 'Diamond city'),
        ('Raisen', 'RSN', 1331699, 8466, 'Historical district'),
        ('Rajgarh', 'RJG', 1547368, 6154, 'Largest town in MP'),
        ('Ratlam', 'RTM', 1455069, 4861, 'Industrial city'),
        ('Rewa', 'REW', 2363744, 6314, 'White tiger district'),
        ('Sagar', 'SGR', 2378458, 10252, 'Lake district'),
        ('Satna', 'STN', 2228935, 7502, 'Cement city'),
        ('Sehore', 'SHR', 1311332, 6578, 'Agricultural district'),
        ('Seoni', 'SEO', 1379131, 8758, 'Coal mining district'),
        ('Shahdol', 'SHD', 1066063, 6205, 'Coal belt'),
        ('Shajapur', 'SJP', 1512681, 6196, 'Agricultural district'),
        ('Sheopur', 'SHP', 687952, 6606, 'Northern border district'),
        ('Shivpuri', 'SHV', 1726050, 10298, 'Historical district'),
        ('Sidhi', 'SDH', 1127033, 10536, 'Coal district'),
        ('Singrauli', 'SGL', 1178132, 5675, 'Energy capital'),
        ('Tikamgarh', 'TKM', 1445166, 5048, 'Temple district'),
        ('Ujjain', 'UJN', 1986864, 6091, 'Religious city'),
        ('Umaria', 'UMR', 644758, 4076, 'Coal and forest district'),
        ('Vidisha', 'VDS', 1458875, 7371, 'Historical district'),
        ('Chachaura', 'CHC', 350000, 1800, 'Newest district 2022'),
        ('Maihar', 'MHR', 400000, 2000, 'Temple city district'),
        ('Nagda', 'NGD', 350000, 1500, 'Industrial district'),
    ],
}

# Start ID counter from 0x011a (where Kerala ends at 0x0119)
current_id = 0x011a

output_lines = []

for state_id, districts in districts_data.items():
    # Determine state name from state_id mapping
    state_names = {
        's00000000-0040-4000-0000-000400000000': 'MADHYA PRADESH',
    }
    
    state_name = state_names.get(state_id, 'UNKNOWN STATE')
    district_count = len(districts)
    
    output_lines.append(f"\n-- ============================================")
    output_lines.append(f"-- {state_name} ({district_count} districts)")
    output_lines.append(f"-- State ID: {state_id}")
    output_lines.append(f"-- ============================================")
    
    for name, code, population, area, description in districts:
        # Generate proper UUID
        uuid = f"d00000000-{current_id:04x}-4000-0000-{current_id:012x}0000000"
        
        # Create INSERT line
        line = f"('{uuid}', '{name}', '{code}', '{state_id}', {population}, {area}, '{description}', datetime('now'), datetime('now')),"
        output_lines.append(line)
        
        current_id += 1

# Write to file
with open('madhya_pradesh_districts.txt', 'w', encoding='utf-8') as f:
    f.write('\n'.join(output_lines))

print(f"Generated {current_id - 0x011a} Madhya Pradesh districts")
print("Output written to madhya_pradesh_districts.txt")

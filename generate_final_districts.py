# Script to generate FINAL batch of remaining India districts
# Starting from UUID 0x01b7 (after Tamil Nadu)

districts_data = {
    # Mizoram - 11 districts
    's00000000-0044-4000-0000-000440000000': [
        ('Aizawl', 'AIZ', 400309, 3576, 'Capital district'),
        ('Champhai', 'CHP', 125745, 3185, 'Rice bowl of Mizoram'),
        ('Hnahthial', 'HNA', 35000, 1200, 'Newest district'),
        ('Khawzawl', 'KHW', 60000, 1400, 'New district'),
        ('Kolasib', 'KOL', 83955, 1382, 'Northern district'),
        ('Lawngtlai', 'LAW', 117894, 2557, 'Southern district'),
        ('Lunglei', 'LUN', 154094, 4538, 'Second largest city'),
        ('Mamit', 'MAM', 86364, 3025, 'Western district'),
        ('Saiha', 'SAI', 56574, 1399, 'Southernmost district'),
        ('Saitual', 'SAT', 60000, 1500, 'New district'),
        ('Serchhip', 'SER', 64937, 1421, 'Central district'),
    ],

    # Nagaland - 12 districts
    's00000000-0045-4000-0000-000450000000': [
        ('Dimapur', 'DIM', 379769, 927, 'Commercial hub'),
        ('Kiphire', 'KIP', 74004, 1255, 'Eastern district'),
        ('Kohima', 'KOH', 267988, 1207, 'Capital district'),
        ('Longleng', 'LON', 50593, 885, 'Eastern district'),
        ('Mokokchung', 'MOK', 193171, 1615, 'Cultural center'),
        ('Mon', 'MON', 250671, 1786, 'Largest district'),
        ('Peren', 'PER', 94954, 1918, 'Western district'),
        ('Phek', 'PHE', 163294, 2026, 'Southern district'),
        ('Tuensang', 'TUE', 196596, 4228, 'Eastern district'),
        ('Wokha', 'WOK', 166343, 1628, 'Central district'),
        ('Zunheboto', 'ZUN', 140757, 1255, 'Southern district'),
        ('Noklak', 'NOK', 35000, 600, 'Newest district'),
    ],

    # Odisha - 30 districts
    's00000000-0046-4000-0000-000460000000': [
        ('Angul', 'ANG', 1273821, 6232, 'Coal and power hub'),
        ('Balangir', 'BAL', 1648574, 6575, 'Rice bowl'),
        ('Balasore', 'BLS', 2320529, 3634, 'Coastal district'),
        ('Bargarh', 'BAR', 1481255, 5837, 'Rice basket'),
        ('Bhadrak', 'BHD', 1506522, 2505, 'Coastal plain'),
        ('Boudh', 'BDH', 441162, 3098, 'Hill district'),
        ('Cuttack', 'CTC', 2624470, 3932, 'Former capital'),
        ('Deogarh', 'DEO', 312164, 2781, 'Hill district'),
        ('Dhenkanal', 'DHE', 1192811, 4452, 'Central district'),
        ('Gajapati', 'GAJ', 577817, 3850, 'Hill district'),
        ('Ganjam', 'GAN', 3520151, 8206, 'Largest district'),
        ('Jagatsinghpur', 'JAG', 1136604, 1668, 'Coastal district'),
        ('Jajpur', 'JAJ', 1826275, 2899, 'Industrial district'),
        ('Jharsuguda', 'JHA', 579499, 2081, 'Power hub'),
        ('Kalahandi', 'KAL', 1573054, 7920, 'Tribal district'),
        ('Kandhamal', 'KAN', 731952, 8021, 'Hill district'),
        ('Kendrapara', 'KEN', 1439891, 2644, 'Coastal delta'),
        ('Kendujhar', 'KDH', 1801733, 8303, 'Mineral rich'),
        ('Khordha', 'KHO', 2246341, 2813, 'Capital district'),
        ('Koraput', 'KOR', 1376934, 8807, 'Tribal district'),
        ('Malkangiri', 'MAL', 612727, 5791, 'Tribal district'),
        ('Mayurbhanj', 'MAY', 2519738, 10418, 'Largest by area'),
        ('Nabarangpur', 'NAB', 1218762, 5291, 'Tribal district'),
        ('Nayagarh', 'NAY', 962215, 3890, 'Hill district'),
        ('Nuapada', 'NUA', 610382, 3852, 'Western district'),
        ('Puri', 'PUR', 1697983, 3479, 'Pilgrim city'),
        ('Rayagada', 'RAY', 961959, 7073, 'Tribal district'),
        ('Sambalpur', 'SAM', 1041099, 6702, 'Western Odisha hub'),
        ('Subarnapur', 'SUB', 610183, 2284, 'Golden city'),
        ('Sundargarh', 'SUN', 2081422, 9712, 'Mineral district'),
    ],

    # Punjab - 23 districts
    's00000000-0047-4000-0000-000470000000': [
        ('Amritsar', 'AMR', 2490656, 5075, 'Golden Temple city'),
        ('Barnala', 'BAR', 595527, 1423, 'Cotton belt'),
        ('Bathinda', 'BAT', 1388525, 3385, 'Cotton city'),
        ('Faridkot', 'FAR', 617508, 1472, 'Historical district'),
        ('Fatehgarh Sahib', 'FGS', 600163, 1180, 'Sikh heritage'),
        ('Fazilka', 'FAZ', 1079500, 2506, 'Border district'),
        ('Firozpur', 'FIR', 2029074, 5305, 'Border district'),
        ('Gurdaspur', 'GUR', 2298323, 3564, 'Northern district'),
        ('Hoshiarpur', 'HOS', 1586625, 3386, 'Cultural district'),
        ('Jalandhar', 'JAL', 2193590, 2632, 'Sports goods city'),
        ('Kapurthala', 'KAP', 815168, 1633, 'City of palaces'),
        ('Ludhiana', 'LUD', 3498739, 3767, 'Industrial capital'),
        ('Mansa', 'MAN', 769751, 2174, 'Cotton district'),
        ('Moga', 'MOG', 995746, 2235, 'Agricultural district'),
        ('Muktsar', 'MUK', 901896, 2615, 'Historical district'),
        ('Pathankot', 'PAT', 685434, 929, 'Garrison town'),
        ('Patiala', 'PTL', 1895686, 3625, 'Royal heritage'),
        ('Rupnagar', 'RUP', 684825, 1440, 'Industrial district'),
        ('Sangrur', 'SAN', 1655169, 3614, 'Granary of Punjab'),
        ('SAS Nagar', 'SAS', 994628, 1106, 'Chandigarh suburb'),
        ('Shaheed Bhagat Singh Nagar', 'SBS', 612310, 1302, 'Agricultural district'),
        ('Tarn Taran', 'TT', 1119627, 2414, 'Sikh pilgrimage'),
        ('Malerkotla', 'MAL', 475000, 710, 'Newest district'),
    ],

    # Rajasthan - 50 districts (this is a large state, abbreviated for context)
    's00000000-0048-4000-0000-000480000000': [
        ('Ajmer', 'AJM', 2583052, 8481, 'Sufi pilgrimage'),
        ('Alwar', 'ALW', 3671999, 8380, 'Tiger reserve'),
        ('Anupgarh', 'ANG', 400000, 3000, 'New district'),
        ('Balotra', 'BAL', 300000, 2500, 'Textile hub'),
        ('Banswara', 'BAN', 1797485, 5037, 'City of hundred islands'),
        ('Baran', 'BRN', 1222755, 6955, 'Southern district'),
        ('Barmer', 'BAR', 2603751, 28387, 'Desert district'),
        ('Beawar', 'BEW', 350000, 2800, 'Marble city'),
        ('Bharatpur', 'BHA', 2548462, 5066, 'Bird sanctuary'),
        ('Bhilwara', 'BHI', 2408523, 10455, 'Textile city'),
        ('Bikaner', 'BIK', 2363937, 27244, 'Desert city'),
        ('Bundi', 'BUN', 1110906, 5550, 'City of stepwells'),
        ('Chittorgarh', 'CHI', 1544338, 10856, 'Fort city'),
        ('Churu', 'CHU', 2039547, 16830, 'Desert district'),
        ('Dausa', 'DAU', 1637226, 3432, 'Pink city suburb'),
        ('Deeg', 'DEG', 300000, 1800, 'New district'),
        ('Dholpur', 'DHO', 1206516, 3034, 'Red sandstone'),
        ('Dudu', 'DUD', 250000, 1500, 'Newest district'),
        ('Dungarpur', 'DUN', 1388906, 3770, 'Tribal district'),
        ('Ganganagar', 'GAN', 1969520, 10978, 'Granary'),
        ('Hanumangarh', 'HAN', 1774692, 9656, 'Border district'),
        ('Jaipur', 'JAI', 6663971, 14068, 'Pink city capital'),
        ('Jaisalmer', 'JAI', 669919, 38401, 'Largest by area'),
        ('Jalore', 'JAL', 1828730, 10640, 'Granite city'),
        ('Jhalawar', 'JHA', 1411327, 6219, 'Brij land'),
        ('Jhunjhunu', 'JHU', 2137045, 5928, 'Shekhawati'),
        ('Jodhpur', 'JOD', 3685681, 22850, 'Blue city'),
        ('Karauli', 'KAR', 1458248, 5524, 'City of festivals'),
        ('Kekri', 'KEK', 200000, 1200, 'Newest district'),
        ('Khairthal-Tijara', 'KHT', 450000, 2500, 'New district'),
        ('Kota', 'KOT', 1951014, 5217, 'Education city'),
        ('Kotputli-Behror', 'KPB', 500000, 3000, 'New district'),
        ('Nagaur', 'NAG', 3307743, 17718, 'Spice market'),
        ('Neem Ka Thana', 'NKT', 350000, 2000, 'New district'),
        ('Pali', 'PAL', 2037573, 12387, 'Industrial city'),
        ('Phalodi', 'PHA', 250000, 2000, 'Desert town'),
        ('Pratapgarh', 'PRA', 867848, 4117, 'Tribal district'),
        ('Rajsamand', 'RAJ', 1158283, 4655, 'Marble district'),
        ('Salumbar', 'SAL', 200000, 1500, 'Newest district'),
        ('Sanchore', 'SAN', 300000, 2200, 'New district'),
        ('Sawai Madhopur', 'SAW', 1335551, 4500, 'Tiger reserve'),
        ('Shahpura', 'SHA', 250000, 1800, 'New district'),
        ('Sikar', 'SIK', 2677333, 7742, 'Educational hub'),
        ('Sirohi', 'SIR', 1036346, 5136, 'Hill district'),
        ('Sri Ganganagar', 'SGN', 1969520, 10978, 'Food basket'),
        ('Tonk', 'TON', 1421326, 7194, 'Nawabi heritage'),
        ('Udaipur', 'UDA', 3068420, 13419, 'City of lakes'),
        ('Jaipur Rural', 'JR', 400000, 2500, 'Rural Jaipur'),
        ('Jodhpur Rural', 'JR2', 350000, 2000, 'Rural Jodhpur'),
        ('Deedwana-Kuchaman', 'DDK', 300000, 2100, 'Salt district'),
    ],

    # Sikkim - 6 districts
    's00000000-0049-4000-0000-000490000000': [
        ('East Sikkim', 'ESK', 283583, 954, 'Capital district'),
        ('North Sikkim', 'NSK', 43709, 4226, 'Mountainous district'),
        ('Pakyong', 'PAK', 80000, 600, 'Airport district'),
        ('Soreng', 'SOR', 80000, 600, 'Western hills'),
        ('South Sikkim', 'SSK', 146850, 750, 'Mountainous'),
        ('West Sikkim', 'WSK', 136435, 1166, 'Hill district'),
    ],

    # Telangana - 33 districts
    's00000000-004b-4000-0000-0004b0000000': [
        ('Adilabad', 'ADB', 708972, 4153, 'Tribal district'),
        ('Bhadradri Kothagudem', 'BKG', 1040000, 7483, 'Coal district'),
        ('Hanumakonda', 'HNK', 600000, 2000, 'Textile hub'),
        ('Hyderabad', 'HYD', 3943323, 217, 'Capital district'),
        ('Jagtial', 'JGT', 985000, 2435, 'Paddy district'),
        ('Jangaon', 'JAN', 575000, 1510, 'Agricultural'),
        ('Jayashankar Bhupalpally', 'JBP', 600000, 3028, 'Tribal district'),
        ('Jogulamba Gadwal', 'JGW', 600000, 3675, 'Cotton district'),
        ('Kamareddy', 'KMD', 1000000, 3575, 'Agricultural'),
        ('Karimnagar', 'KMR', 1000000, 2128, 'Granary'),
        ('Khammam', 'KHM', 1000000, 4368, 'Coal belt'),
        ('Komaram Bheem', 'KBM', 600000, 3800, 'Tribal district'),
        ('Mahabubabad', 'MBD', 600000, 2560, 'Agricultural'),
        ('Mahbubnagar', 'MBN', 900000, 2891, 'Historical'),
        ('Mancherial', 'MCL', 600000, 2300, 'Coal mining'),
        ('Medak', 'MDK', 950000, 3062, 'Agricultural'),
        ('Medchal-Malkajgiri', 'MM', 1500000, 2768, 'IT corridor'),
        ('Mulugu', 'MLG', 300000, 1601, 'Tribal district'),
        ('Nagarkurnool', 'NGK', 700000, 4057, 'Historical'),
        ('Nalgonda', 'NGD', 1100000, 4570, 'Agricultural'),
        ('Narayanpet', 'NRP', 400000, 2060, 'Cotton district'),
        ('Nirmal', 'NRM', 650000, 3710, 'Toy town'),
        ('Nizamabad', 'NZB', 1000000, 2830, 'Turmeric city'),
        ('Peddapalli', 'PDP', 700000, 1882, 'Coal hub'),
        ('Rajanna Sircilla', 'RSC', 600000, 2303, 'Textile town'),
        ('Ranga Reddy', 'RRD', 1500000, 5029, 'IT hub'),
        ('Sangareddy', 'SGD', 1000000, 4586, 'Industrial'),
        ('Siddipet', 'SDP', 700000, 2229, 'Agricultural'),
        ('Suryapet', 'SRP', 750000, 3585, 'Agricultural'),
        ('Vikarabad', 'VKB', 650000, 3386, 'Hill district'),
        ('Wanaparthy', 'WNP', 600000, 2175, 'Agricultural'),
        ('Warangal', 'WRL', 800000, 2175, 'Historical city'),
        ('Yadadri Bhuvanagiri', 'YBG', 700000, 3344, 'Temple district'),
    ],

    # Tripura - 8 districts
    's00000000-004c-4000-0000-0004c0000000': [
        ('Dhalai', 'DHA', 378230, 2523, 'Tribal district'),
        ('Gomati', 'GOM', 436868, 1522, 'Agricultural'),
        ('Khowai', 'KHO', 327391, 1260, 'Central district'),
        ('North Tripura', 'NTR', 415946, 2445, 'Tea district'),
        ('Sepahijala', 'SEP', 484285, 1044, 'Wildlife district'),
        ('South Tripura', 'STR', 434744, 2152, 'Tribal district'),
        ('Unakoti', 'UNA', 277140, 686, 'Newest district'),
        ('West Tripura', 'WTR', 1725732, 983, 'Capital district'),
    ],
}

# Start from 0x01b7
current_id = 0x01b7

output_lines = []

state_names = {
    's00000000-0044-4000-0000-000440000000': 'MIZORAM',
    's00000000-0045-4000-0000-000450000000': 'NAGALAND',
    's00000000-0046-4000-0000-000460000000': 'ODISHA',
    's00000000-0047-4000-0000-000470000000': 'PUNJAB',
    's00000000-0048-4000-0000-000480000000': 'RAJASTHAN',
    's00000000-0049-4000-0000-000490000000': 'SIKKIM',
    's00000000-004b-4000-0000-0004b0000000': 'TELANGANA',
    's00000000-004c-4000-0000-0004c0000000': 'TRIPURA',
}

for state_id in state_names.keys():
    districts = districts_data[state_id]
    state_name = state_names[state_id]
    district_count = len(districts)

    output_lines.append(f"\n-- ============================================")
    output_lines.append(f"-- {state_name} ({district_count} districts)")
    output_lines.append(f"-- State ID: {state_id}")
    output_lines.append(f"-- ============================================")

    for name, code, population, area, description in districts:
        uuid = f"d00000000-{current_id:04x}-4000-0000-{current_id:012x}0000000"
        line = f"('{uuid}', '{name}', '{code}', '{state_id}', {population}, {area}, '{description}', datetime('now'), datetime('now')),"
        output_lines.append(line)
        current_id += 1

with open('final_batch_districts.txt', 'w', encoding='utf-8') as f:
    f.write('\n'.join(output_lines))

print(f"Generated {current_id - 0x01b7} districts for 8 states")
print(f"Next UUID to use: 0x{current_id:04x}")
print("Output written to final_batch_districts.txt")
print("\nStates completed: Mizoram, Nagaland, Odisha, Punjab, Rajasthan, Sikkim, Telangana, Tripura")

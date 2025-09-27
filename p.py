n = int(input())
current_num = 1

for row in range(n):
    # Create a list for the current row
    row_numbers = []
    for col in range(n):
        row_numbers.append(str(current_num))
        current_num += 1
    
    # Reverse for even-indexed rows (1-based)
    if row % 2 == 1:
        row_numbers.reverse()
    
    # Join numbers and right-align
    row_str = ''.join(row_numbers)
    print(row_str.rjust(n))